<?php


declare( strict_types = 1 );


namespace NFSN\APIClient;


interface SiteInterface {


    public function addAlias( string $i_stAlias ) : bool|string;


    public function removeAlias( string $i_stAlias ) : bool|string;


}