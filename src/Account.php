<?php


declare( strict_types = 1 );


namespace NFSN\APIClient;


class Account extends AbstractApiObject {


    public function __construct( ManagerInterface $i_api, string $i_stId ) {
        parent::__construct( $i_api, 'account', $i_stId );
    }


    public function addSite( string $i_stSite ) : void {
        $this->requestPost( 'addSite', [
            'site' => $i_stSite,
        ] );
    }


    public function addWarning( float $i_fBalance ) : bool|string {
        return $this->requestPost( 'addWarning', [ 'balance' => $i_fBalance ] );
    }


    public function getBalance() : float {
        return (float) $this->requestGet( 'balance' );
    }


    public function getBalanceCash() : float {
        return (float) $this->requestGet( 'balanceCash' );
    }


    public function getBalanceCredit() : float {
        return (float) $this->requestGet( 'balanceCredit' );
    }


    public function getBalanceHigh() : float {
        return (float) $this->requestGet( 'balanceHigh' );
    }


    public function getFriendlyName() : string {
        return (string) $this->requestGet( 'friendlyName' );
    }


    public function getSites() : bool|string {
        $res = $this->requestGet( 'sites' );
        if ( ! $res ) {
            return false;
        }
        return $res;
    }


    public function getStatus() : bool|string {
        $res = $this->requestGet( 'status' );
        if ( ! $res ) {
            return false;
        }
        return $res;
    }


    public function putFriendlyName( string $i_friendlyName ) : bool {
        return $this->requestPut( 'friendlyName', $i_friendlyName );
    }


    public function removeWarning( float $i_fBalance ) : bool|string {
        return $this->requestPost( 'removeWarning', [ 'balance' => $i_fBalance ] );
    }


}
