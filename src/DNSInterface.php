<?php


declare( strict_types = 1 );


namespace NFSN\APIClient;


interface DNSInterface {


    public function addRR( string  $i_stName, string $i_stType, string $i_stData,
                           ?string $i_nstTtl = null ) : bool|string;


    public function getExpire() : int;


    public function getMinTTL() : int;


    public function getRefresh() : int;


    public function getRetry() : int;


    public function getSerial() : int;


    /** @return array<int|string, mixed>|bool */
    public function listRRs( ?string $i_nstName = null, ?string $i_nstType = null,
                             ?string $i_nstData = null, ?string $i_nstScope = null ) : array|bool;


    public function putExpire( int $i_uExpire ) : bool;


    public function putMinTTL( int $i_uTtl ) : bool;


    public function putRefresh( int $i_uRefresh ) : bool;


    public function putRetry( int $i_uRetry ) : bool;


    public function removeRR( ?string $i_nstName, ?string $i_nstType, ?string $i_nstData ) : bool|string;


    public function replaceRR( string $i_stName, string $i_stType, string $i_stData,
                               ?int   $i_nuTtl = null ) : bool|string;


    public function sync() : float;


    public function updateSerial() : bool|string;


}