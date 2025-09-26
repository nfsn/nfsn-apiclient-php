<?php


declare( strict_types = 1 );


namespace NFSN\APIClient;


interface EmailInterface {


    /** @return array<string, string>|bool */
    public function listForwards() : array|bool;


    public function removeForward( string $i_stForward ) : bool|string;


    public function setForward( string $i_stForward, string $i_stDestEmail ) : bool|string;


}