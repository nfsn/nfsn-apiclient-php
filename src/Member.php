<?php


declare( strict_types = 1 );


namespace NFSN\APIClient;


class Member extends AbstractApiObject {


    public function __construct( ManagerInterface $i_api, string $i_stId ) {
        parent::__construct( $i_api, 'member', $i_stId );
    }


    public function getAccounts() : bool|string {
        $res = $this->requestGet( 'accounts' );
        if ( ! $res ) {
            return false;
        }
        return $res;
    }


    public function getSites() : bool|string {
        $res = $this->requestGet( 'sites' );
        if ( ! $res ) {
            return false;
        }
        return $res;
    }


}


