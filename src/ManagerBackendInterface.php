<?php


declare( strict_types = 1 );


namespace NFSN\APIClient;


interface ManagerBackendInterface {


    public function getLastBody() : string;


    public function requestGet( string $i_stType, string $i_stId, string $i_stMember ) : bool|string;


    /** @param array<string, float|int|string> $i_rParams */
    public function requestPost( string $i_stType, string $i_stId, string $i_stMethod, array $i_rParams ) : bool;


    public function requestPut( string $i_stType, string $i_stID, string $i_stMember, string $i_stValue ) : bool;


}

