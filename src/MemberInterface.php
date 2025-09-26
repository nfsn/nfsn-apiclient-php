<?php


declare( strict_types = 1 );


namespace NFSN\APIClient;


interface MemberInterface {


    public function getAccounts() : bool|string;


    /** @return list<string>|bool */
    public function getSites() : array|bool;


}