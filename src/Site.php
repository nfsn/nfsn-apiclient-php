<?php


declare( strict_types = 1 );


namespace NFSN\APIClient;


class Site extends AbstractApiObject {


    public function __construct( ManagerInterface $i_api, string $i_stId ) {
        parent::__construct( $i_api, 'site', $i_stId );
    }


    public function addAlias( string $i_stAlias ) : bool|string {
        $r = [ 'alias' => $i_stAlias ];
        return $this->requestPost( 'addAlias', $r );
    }


    public function removeAlias( string $i_stAlias ) : bool|string {
        $r = [ 'alias' => $i_stAlias ];
        return $this->requestPost( 'removeAlias', $r );
    }


}


