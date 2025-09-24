<?php


declare( strict_types = 1 );


namespace NFSN\APIClient;


class DNS extends AbstractApiObject {


    public function __construct( ManagerInterface $i_api, string $i_stId ) {
        parent::__construct( $i_api, 'dns', $i_stId );
    }


    public function addRR( string  $i_stName, string $i_stType, string $i_stData,
                           ?string $i_nstTtl = null ) : bool|string {
        $r = [];
        $r[ 'name' ] = $i_stName;
        $r[ 'type' ] = $i_stType;
        $r[ 'data' ] = $i_stData;
        if ( $i_nstTtl ) {
            $r[ 'ttl' ] = $i_nstTtl;
        }
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


    public function listRRs( ?string $i_nstName = null, ?string $i_nstType = null,
                             ?string $i_nstData = null, ?string $i_nstScope = null ) : bool|string {
        $r = [];
        if ( $i_nstName ) {
            $r[ 'name' ] = $i_nstName;
        }
        if ( $i_nstType ) {
            $r[ 'type' ] = $i_nstType;
        }
        if ( $i_nstData ) {
            $r[ 'data' ] = $i_nstData;
        }
        if ( $i_nstScope ) {
            $r[ 'scope' ] = $i_nstScope;
        }
        $res = $this->requestPost( 'listRRs', $r );
        if ( ! $res ) {
            return false;
        }
        return $res;
    }


    public function putExpire( int $i_uExpire ) : bool {
        return $this->requestPut( 'expire', (string) $i_uExpire );
    }


    public function putMinTTL( int $i_uTtl ) : bool {
        return $this->requestPut( 'minTTL', (string) $i_uTtl );
    }


    public function putRefresh( int $i_uRefresh ) : bool {
        return $this->requestPut( 'refresh', (string) $i_uRefresh );
    }


    public function putRetry( int $i_uRetry ) : bool {
        return $this->requestPut( 'retry', (string) $i_uRetry );
    }


    public function removeRR( ?string $i_nstName, ?string $i_nstType, ?string $i_nstData ) : bool|string {
        $r = [];
        if ( $i_nstName ) {
            $r[ 'name' ] = $i_nstName;
        }
        if ( $i_nstType ) {
            $r[ 'type' ] = $i_nstType;
        }
        if ( $i_nstData ) {
            $r[ 'data' ] = $i_nstData;
        }
        return $this->requestPost( 'removeRR', $r );
    }


    public function sync() : float {
        return floatval( $this->requestGet( 'sync' ) );
    }


    public function updateSerial() : bool|string {
        return $this->requestPost( 'updateSerial', [] );
    }


}


