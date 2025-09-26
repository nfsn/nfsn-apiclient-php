<?php


declare( strict_types = 1 );


namespace NFSN\APIClient;


use JDWX\Json\Json;


abstract class AbstractApiObject {


    protected ManagerBackendInterface $api;

    protected string $strID;

    protected string $strType;


    public function __construct( ManagerBackendInterface $i_api, string $i_stType, string $i_strID ) {
        $this->strType = $i_stType;
        $this->api =& $i_api;
        $this->strID = $i_strID;
    }


    public function requestGet( string $i_stProperty ) : bool|string {
        return $this->api->requestGet( $this->strType, $this->strID, $i_stProperty );
    }


    /** @param array<string, float|int|string> $i_rParams */
    public function requestPost( string $i_stMethod, array $i_rParams ) : bool|string {
        if ( ! $this->api->requestPost( $this->strType, $this->strID, $i_stMethod, $i_rParams ) ) {
            return false;
        }
        $body = trim( $this->api->getLastBody() );
        if ( $body ) {
            return $body;
        }
        return true;
    }


    public function requestPut( string $i_stProperty, string $i_stValue ) : bool {
        return $this->api->requestPut( $this->strType, $this->strID, $i_stProperty, $i_stValue );
    }


    /** @return array<int|string, mixed>|bool */
    protected function decodeArray( bool|string $i_bst ) : array|bool {
        if ( is_bool( $i_bst ) ) {
            return $i_bst;
        }
        return Json::decodeArray( $i_bst );
    }


}


