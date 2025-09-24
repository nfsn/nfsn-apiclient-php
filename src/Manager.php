<?php
declare( strict_types = 1 );


namespace NFSN\APIClient;


require_once __DIR__ . '/IManager.php';
require_once __DIR__ . '/BaseObject.php';
require_once __DIR__ . '/Account.php';
require_once __DIR__ . '/DNS.php';
require_once __DIR__ . '/Email.php';
require_once __DIR__ . '/Site.php';
require_once __DIR__ . '/Member.php';


class Manager implements IManager {


    protected bool $bDebug;
    protected int $iAPIPort;

    protected ?string $lastError = null;
    protected int $lStatus;
    protected string $strAPIHost;
    protected string $strAPIKey;
    protected string $strBody;
    protected string $strLogin;
    protected string $strStatus;


    public function __construct( string $i_strLogin, string $i_strAPIKey, bool $i_bDebug = false ) {
        $this->strLogin = $i_strLogin;
        $this->strAPIKey = $i_strAPIKey;
        $this->lastError = null;
        $this->bDebug = $i_bDebug;
        $this->setAPIServer();
    }


    public function calculateAuthHash( string $i_strURI, string $i_strBodyHash ) : string {
        $tmStamp = time();
        $strSalt = static::newSalt();
        $strCheck = "{$this->strLogin};{$tmStamp};{$strSalt};{$this->strAPIKey};{$i_strURI};{$i_strBodyHash}";
        $strHash = sha1( $strCheck );
        return "{$this->strLogin};{$tmStamp};{$strSalt};{$strHash}";
    }


    public function getLastBody() : string {
        return $this->strBody;
    }


    public function getLastError() : string {
        if ( $this->lastError === null )
            return '(No error.)';
        return $this->lastError;
    }


    public function handleError( string $i_strBody ) : void {
        $strPattern = '/^{"error":"(.*)","debug":"(.*)"}$/';
        $this->lastError = preg_replace( $strPattern, '$1', $i_strBody );
        $strDebug = preg_replace( $strPattern, '$2', $i_strBody );
        error_log( "APIManager: API error: {$this->lastError}" );
        if ( $strDebug )
            error_log( "APIManager: API debug: {$strDebug}" );
    }


    public function newAccount( string $i_strID ) : Account {
        return new Account( $this, $i_strID );
    }


    public function newDNS( $i_strID ) : DNS {
        return new DNS( $this, $i_strID );
    }


    public function newEmail( $i_strID ) : Email {
        return new Email( $this, $i_strID );
    }


    public function newMember( $i_strID ) : Member {
        return new Member( $this, $i_strID );
    }


    public function getMember() : Member {
        return $this->newMember( $this->strLogin );
    }


    public static function newSalt() : string {
        $strChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $strOut = '';
        while ( strlen( $strOut ) < 16 )
            $strOut .= substr( $strChars, rand( 0, strlen( $strChars ) - 1 ), 1 );
        return $strOut;
    }


    public function newSite( string $i_strID ) : Site {
        return new Site( $this, $i_strID );
    }


    public function request( $i_strMethod, $i_strType, $i_strID, $i_strMember, $i_strBody, $i_strContentType = 'application/x-nfsn-api' ) : void {
        $strURI = '/' . $i_strType . '/' . $i_strID . '/' . $i_strMember;
        $strHash = $this->calculateAuthHash( $strURI, sha1( $i_strBody ) );

        $f = fsockopen( 'ssl://' . $this->strAPIHost, $this->iAPIPort );
        fwrite( $f, $i_strMethod . " {$strURI} HTTP/1.0\r\n" );
        fwrite( $f, "Host: {$this->strAPIHost}\r\n" );
        fwrite( $f, 'Content-Length: ' . strlen( $i_strBody ) . "\r\n" );
        fwrite( $f, "Content-Type: {$i_strContentType}\r\n" );
        fwrite( $f, "Connection: close\r\n" );
        fwrite( $f, "X-NFSN-Authentication: {$strHash}\r\n\r\n" );
        fwrite( $f, $i_strBody );

        $bHeaders = true;
        $arrHeaders = [];
        $this->strBody = '';
        $line = fgets( $f );

        $this->strStatus = preg_replace( "/[\n\r]/", '', $line );
        while ( $line !== false ) {
            $line = preg_replace( "/[\n\r]/", '', $line );
            if ( $bHeaders ) {
                if ( $line == '' ) $bHeaders = false;
                elseif ( $line != $this->strStatus ) {
                    $arr = explode( ': ', $line );
                    $arrHeaders[ array_shift( $arr ) ] = join( ': ', $arr );
                }
            } else $this->strBody .= $line;
            $line = fgets( $f );
        }

        $this->lStatus = (int) preg_replace( '#^HTTP/1\.[01] ([0-9]+).*$#', '$1', $this->strStatus );

        if ( $this->lStatus != 200 )
            $this->handleError( $this->strBody );
        if ( $this->bDebug ) {
            print "Status: {$this->strStatus} ($this->lStatus)\n";
            print 'Headers: ';
            print_r( $arrHeaders );
            print "\nBody:\n{$this->strBody}\n";
        }
    }


    public function requestGet( string $i_strType, string $i_strID, string $i_strMember ) : bool|string {
        $this->request( 'GET', $i_strType, $i_strID, $i_strMember, '' );
        if ( $this->lStatus != 200 ) return false;
        return $this->strBody;
    }


    public function requestPost( string $i_strType, string $i_strID, string $i_strMethod, array $i_arrParams ) : bool {
        $strParams = '';
        foreach ( $i_arrParams as $arg => $val )
            $strParams .= urlencode( $arg ) . '=' . urlencode( $val ) . '&';
        // print $strParams ."\n";
        $this->request( 'POST', $i_strType, $i_strID, $i_strMethod, $strParams, 'application/x-www-form-urlencoded' );
        return ( $this->lStatus == 200 );
    }


    public function requestPut( $i_strType, $i_strID, $i_strMember, $i_strValue ) : bool {
        $this->request( 'PUT', $i_strType, $i_strID, $i_strMember, $i_strValue );
        return ( $this->lStatus == 200 );
    }


    public function setAPIServer( $i_strHost = 'api.nearlyfreespeech.net',
                                  $i_intPort = 443 ) : void {
        $this->strAPIHost = $i_strHost;
        $this->iAPIPort = $i_intPort;
    }


}


