<?php
declare( strict_types = 1 );


namespace NFSN\APIClient;


require_once __DIR__ . '/BaseObject.php';


class DNS extends BaseObject {


    public function __construct( IManager $i_api, string $i_strID ) {
        parent::__construct( $i_api, 'dns', $i_strID );
    }


    public function addRR( $i_strName, $i_strType, $i_strData, $i_strTTL = null ) : bool|string {
        $r = [];
        $r[ 'name' ] = $i_strName;
        $r[ 'type' ] = $i_strType;
        $r[ 'data' ] = $i_strData;
        if ( $i_strTTL )
            $r[ 'ttl' ] = $i_strTTL;
        return $this->requestPost( 'addRR', $r );
    }


    public function getExpire() : int {
        return (int) $this->requestGet( 'expire' );
    }


    public function getMinTTL() : int {
        return (int) $this->requestGet( 'minTTL' );
    }


    public function getRefresh() : int {
        return (int) $this->requestGet( 'refresh' );
    }


    public function getRetry() : int {
        return (int) $this->requestGet( 'retry' );
    }


    public function getSerial() : int {
        return (int) $this->requestGet( 'serial' );
    }


    public function listRRs( ?string $i_strName = null, ?string $i_strType = null,
                             ?string $i_strData = null, ?string $i_strScope = null ) : bool|string {
        $r = [];
        if ( $i_strName ) $r[ 'name' ] = $i_strName;
        if ( $i_strType ) $r[ 'type' ] = $i_strType;
        if ( $i_strData ) $r[ 'data' ] = $i_strData;
        if ( $i_strScope ) $r[ 'scope' ] = $i_strScope;
        $res = $this->requestPost( 'listRRs', $r );
        if ( ! $res ) return false;
        return $res;
    }


    public function putExpire( int $i_expire ) : bool {
        return $this->requestPut( 'expire', (string) $i_expire );
    }


    public function putMinTTL( int $i_ttl ) : bool {
        return $this->requestPut( 'minTTL', (string) $i_ttl );
    }


    public function putRefresh( int $i_refresh ) : bool {
        return $this->requestPut( 'refresh', (string) $i_refresh );
    }


    public function putRetry( int $i_retry ) : bool {
        return $this->requestPut( 'retry', (string) $i_retry );
    }


    public function removeRR( ?string $i_strName, ?string $i_strType, ?string $i_strData ) : bool|string {
        $r = [];
        if ( $i_strName ) $r[ 'name' ] = $i_strName;
        if ( $i_strType ) $r[ 'type' ] = $i_strType;
        if ( $i_strData ) $r[ 'data' ] = $i_strData;
        return $this->requestPost( 'removeRR', $r );
    }


    public function sync() : float {
        return floatval( $this->requestGet( 'sync' ) );
    }


    public function updateSerial() : bool|string {
        return $this->requestPost( 'updateSerial', [] );
    }


}


