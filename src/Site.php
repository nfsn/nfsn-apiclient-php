<?php
declare( strict_types = 1 );


namespace NFSN\APIClient;


require_once __DIR__ . '/BaseObject.php';


class Site extends BaseObject {


    public function __construct( IManager $i_api, $i_strID ) {
        parent::__construct( $i_api, 'site', $i_strID );
    }


    public function addAlias( string $i_strAlias ) : bool|string {
        $r = [ 'alias' => $i_strAlias ];
        return $this->requestPost( 'addAlias', $r );
    }


    public function removeAlias( string $i_strAlias ) : bool|string {
        $r = [ 'alias' => $i_strAlias ];
        return $this->requestPost( 'removeAlias', $r );
    }


}


