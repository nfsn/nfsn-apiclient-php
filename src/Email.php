<?php


declare( strict_types = 1 );


namespace NFSN\APIClient;


class Email extends AbstractApiObject implements EmailInterface {


    public function __construct( ManagerBackendInterface $i_api, string $i_stId ) {
        parent::__construct( $i_api, 'email', $i_stId );
    }


    /** @return array<string, string>|bool */
    public function listForwards() : array|bool {
        $bst = $this->requestPost( 'listForwards', [] );
        if ( ! is_string( $bst ) ) {
            return $bst;
        }
        /** @phpstan-ignore return.type */
        return $this->decodeArray( $bst );
    }


    public function removeForward( string $i_stForward ) : bool|string {
        $r = [ 'forward' => $i_stForward ];
        return $this->requestPost( 'removeForward', $r );
    }


    public function setForward( string $i_stForward, string $i_stDestEmail ) : bool|string {
        $r = [
            'forward' => $i_stForward,
            'dest_email' => $i_stDestEmail,
        ];
        return $this->requestPost( 'setForward', $r );
    }


}


