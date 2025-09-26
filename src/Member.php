<?php


declare( strict_types = 1 );


namespace NFSN\APIClient;


class Member extends AbstractApiObject implements MemberInterface {


    public function __construct( ManagerBackendInterface $i_api, string $i_stId ) {
        parent::__construct( $i_api, 'member', $i_stId );
    }


    public function getAccounts() : bool|string {
        $res = $this->requestGet( 'accounts' );
        if ( ! $res ) {
            return false;
        }
        return $res;
    }


    /** @return list<string>|bool */
    public function getSites() : array|bool {
        $res = $this->requestGet( 'sites' );
        /** @phpstan-ignore return.type */
        return $this->decodeArray( $res );
    }


}


