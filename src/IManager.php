<?php
declare( strict_types = 1 );


namespace NFSN\APIClient;


interface IManager {

    public function getLastBody() : string;
    public function requestGet( string $i_strType, string $i_strID, string $i_strMember ) : bool|string;
    public function requestPost( string $i_strType, string $i_strID, string $i_strMethod, array $i_arrParams ) : bool;
    public function requestPut( $i_strType, $i_strID, $i_strMember, $i_strValue ) : bool;

}

