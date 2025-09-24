<?php
declare( strict_types = 1 );


namespace NFSN\APIClient;


require_once __DIR__ . '/BaseObject.php';


class Member extends BaseObject {


    public function __construct( IManager $i_api, string $i_strID ) {
        parent::__construct( $i_api, 'member', $i_strID );
    }


    public function getAccounts() : bool|string {
        $res = $this->requestGet( 'accounts' );
        if ( ! $res ) return false;
        return $res;
    }


    public function getSites() : bool|string {
        $res = $this->requestGet( 'sites' );
        if ( ! $res ) return false;
        return $res;
    }

}


