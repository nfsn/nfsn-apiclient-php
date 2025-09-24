<?php
declare( strict_types = 1 );


namespace NFSN\APIClient;


require_once __DIR__ . '/IManager.php';


class BaseObject {


    protected IManager $api;
    protected string $strID;
    protected string $strType;


    public function __construct( IManager $i_api, string $i_strType, string $i_strID ) {
        $this->strType = $i_strType;
        $this->api =& $i_api;
        $this->strID = $i_strID;
    }


    public function requestGet( string $i_strProperty ) : bool|string {
        return $this->api->requestGet( $this->strType, $this->strID, $i_strProperty );
    }


    public function requestPost( string $i_strMethod, array $i_arrParams ) : bool|string {
        if ( ! $this->api->requestPost( $this->strType, $this->strID, $i_strMethod, $i_arrParams ) ) return false;
        $body = trim( $this->api->getLastBody() );
        if ( $body ) return $body;
        return true;
    }


    public function requestPut( string $i_strProperty, string $i_strValue ) : bool {
        return $this->api->requestPut( $this->strType, $this->strID, $i_strProperty, $i_strValue );
    }

}


