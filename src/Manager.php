<?php


declare( strict_types = 1 );


namespace NFSN\APIClient;


use JDWX\Strict\OK;


class Manager implements ManagerInterface {


    protected bool $bDebug;

    protected int $iAPIPort;

    protected ?string $lastError = null;

    protected int $uStatus;

    protected string $stAPIHost;

    protected string $stAPIKey;

    protected string $stBody;

    protected string $stLogin;

    protected string $stStatus;


    public function __construct( string $i_strLogin, string $i_strAPIKey, bool $i_bDebug = false ) {
        $this->stLogin = $i_strLogin;
        $this->stAPIKey = $i_strAPIKey;
        $this->lastError = null;
        $this->bDebug = $i_bDebug;
        $this->setAPIServer();
    }


    public static function newSalt() : string {
        $strChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $strOut = '';
        while ( strlen( $strOut ) < 16 ) {
            $strOut .= $strChars[ random_int( 0, strlen( $strChars ) - 1 ) ];
        }
        return $strOut;
    }


    public function calculateAuthHash( string $i_strURI, string $i_strBodyHash ) : string {
        $tmStamp = time();
        $strSalt = static::newSalt();
        $strCheck = "{$this->stLogin};{$tmStamp};{$strSalt};{$this->stAPIKey};{$i_strURI};{$i_strBodyHash}";
        $strHash = sha1( $strCheck );
        return "{$this->stLogin};{$tmStamp};{$strSalt};{$strHash}";
    }


    public function getLastBody() : string {
        return $this->stBody;
    }


    public function getLastError() : string {
        return $this->lastError ?? '(No error.)';
    }


    public function getMember() : Member {
        return $this->newMember( $this->stLogin );
    }


    public function handleError( string $i_stBody ) : void {
        $strPattern = '/^{"error":"(.*)","debug":"(.*)"}$/';
        $this->lastError = preg_replace( $strPattern, '$1', $i_stBody );
        $strDebug = preg_replace( $strPattern, '$2', $i_stBody );
        error_log( "APIManager: API error: {$this->lastError}" );
        if ( $strDebug ) {
            error_log( "APIManager: API debug: {$strDebug}" );
        }
    }


    public function newAccount( string $i_stId ) : Account {
        return new Account( $this, $i_stId );
    }


    public function newDNS( string $i_stId ) : DNS {
        return new DNS( $this, $i_stId );
    }


    public function newEmail( string $i_stId ) : Email {
        return new Email( $this, $i_stId );
    }


    public function newMember( string $i_stId ) : Member {
        return new Member( $this, $i_stId );
    }


    public function newSite( string $i_strID ) : Site {
        return new Site( $this, $i_strID );
    }


    public function request( string $i_stMethod, string $i_stType, string $i_stId,
                             string $i_stMember, string $i_stBody,
                             string $i_stContentType = 'application/x-nfsn-api' ) : void {
        $strURI = '/' . $i_stType . '/' . $i_stId . '/' . $i_stMember;
        $strHash = $this->calculateAuthHash( $strURI, sha1( $i_stBody ) );

        if ( $this->bDebug ) {
            print "Request: {$i_stMethod} {$strURI}\n";
        }
        $f = OK::fsockopen( 'ssl://' . $this->stAPIHost, $this->iAPIPort );
        fwrite( $f, $i_stMethod . " {$strURI} HTTP/1.0\r\n" );
        fwrite( $f, "Host: {$this->stAPIHost}\r\n" );
        fwrite( $f, 'Content-Length: ' . strlen( $i_stBody ) . "\r\n" );
        fwrite( $f, "Content-Type: {$i_stContentType}\r\n" );
        fwrite( $f, "Connection: close\r\n" );
        fwrite( $f, "X-NFSN-Authentication: {$strHash}\r\n\r\n" );
        fwrite( $f, $i_stBody );

        $bHeaders = true;
        $arrHeaders = [];
        $this->stBody = '';
        $line = OK::fgets( $f );

        $this->stStatus = OK::preg_replace_string( "/[\n\r]/", '', $line );
        while ( $line !== false ) {
            $line = preg_replace( "/[\n\r]/", '', $line );
            if ( $bHeaders ) {
                if ( empty( $line ) ) {
                    $bHeaders = false;
                } elseif ( $line !== $this->stStatus ) {
                    $arr = explode( ': ', $line );
                    $arrHeaders[ array_shift( $arr ) ] = implode( ': ', $arr );
                }
            } else {
                $this->stBody .= $line;
            }
            $line = fgets( $f );
        }

        $this->uStatus = (int) preg_replace( '#^HTTP/1\.[01] (\d+).*$#', '$1', $this->stStatus );

        if ( $this->uStatus !== 200 ) {
            $this->handleError( $this->stBody );
        }
        if ( $this->bDebug ) {
            print "Status: {$this->stStatus} ($this->uStatus)\n";
            print 'Headers: ';
            print_r( $arrHeaders );
            print "\nBody:\n{$this->stBody}\n";
        }
    }


    public function requestGet( string $i_stType, string $i_stId, string $i_stMember ) : bool|string {
        $this->request( 'GET', $i_stType, $i_stId, $i_stMember, '' );
        if ( $this->uStatus !== 200 ) {
            return false;
        }
        return $this->stBody;
    }


    /** @param array<string, float|int|string> $i_rParams */
    public function requestPost( string $i_stType, string $i_stId, string $i_stMethod, array $i_rParams ) : bool {
        $strParams = '';
        foreach ( $i_rParams as $arg => $val ) {
            $strParams .= urlencode( strval( $arg ) ) . '=' . urlencode( strval( $val ) ) . '&';
        }
        // print $strParams ."\n";
        $this->request( 'POST', $i_stType, $i_stId, $i_stMethod, $strParams, 'application/x-www-form-urlencoded' );
        return ( $this->uStatus === 200 );
    }


    public function requestPut( string $i_stType, string $i_stID, string $i_stMember, string $i_stValue ) : bool {
        $this->request( 'PUT', $i_stType, $i_stID, $i_stMember, $i_stValue );
        return ( $this->uStatus === 200 );
    }


    public function setAPIServer( string $i_stHost = 'api.nearlyfreespeech.net',
                                  int    $i_uPort = 443 ) : void {
        $this->stAPIHost = $i_stHost;
        $this->iAPIPort = $i_uPort;
    }


}


