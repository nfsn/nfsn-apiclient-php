<?php


declare( strict_types = 1 );


namespace NFSN\APIClient;


interface AccountInterface {


    public function addSite( string $i_stSite ) : void;


    public function addWarning( float $i_fBalance ) : bool|string;


    public function getBalance() : float;


    public function getBalanceCash() : float;


    public function getBalanceCredit() : float;


    public function getBalanceHigh() : float;


    public function getFriendlyName() : string;


    public function getSites() : bool|string;


    public function getStatus() : bool|string;


    public function putFriendlyName( string $i_friendlyName ) : bool;


    public function removeWarning( float $i_fBalance ) : bool|string;


}