<?php


declare( strict_types = 1 );


namespace NFSN\APIClient;


interface ManagerInterface {


    public function newAccount( string $i_stId ) : AccountInterface;


    public function newDNS( string $i_stId ) : DNSInterface;


    public function newEmail( string $i_stId ) : EmailInterface;


}