<?php
declare( strict_types = 1 );


namespace NFSN\APIClient;


require_once __DIR__ . '/BaseObject.php';


class Email extends BaseObject {


    public function __construct( IManager $i_api, string $i_strID ) {
        parent::__construct( $i_api, 'email', $i_strID );
    }


    public function listForwards() : bool|string {
        return $this->requestPost( 'listForwards', [] );
    }


    public function removeForward( string $i_strForward ) : bool|string {
        $r = [ 'forward' => $i_strForward ];
        return $this->requestPost( 'removeForward', $r );
    }


    public function setForward( string $i_strForward, string $i_strDestEmail ) : bool|string {
        $r = [
            'forward' => $i_strForward,
            'dest_email' => $i_strDestEmail,
        ];
        return $this->requestPost( 'setForward', $r );
    }


}


