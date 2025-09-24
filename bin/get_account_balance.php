<?php


declare( strict_types = 1 );


use NFSN\APIClient\Manager;


require_once __DIR__ . '/../vendor/autoload.php';


/** @param list<string> $argv */
( static function ( array $argv ) : void {

    array_shift( $argv ); // Remove script name from arguments.

    if ( empty( $stAccount = array_shift( $argv ) ) ) {
        echo "Usage: php get_account_balance.php <account_id>\n";
        exit( 1 );
    }
    assert( is_string( $stAccount ) );

    if ( empty( $_ENV[ 'NFSN_API_KEY' ] ) ) {
        echo "NFSN_API_KEY environment variable not set.\n";
        exit( 1 );
    }

    if ( empty( $_ENV[ 'NFSN_API_USER' ] ) ) {
        echo "NFSN_API_USER environment variable not set.\n";
        exit( 1 );
    }

    $api = new Manager( $_ENV[ 'NFSN_API_USER' ], $_ENV[ 'NFSN_API_KEY' ] );
    $act = $api->newAccount( $stAccount );
    $balance = $act->getBalance();
    echo "Account balance: {$balance}\n";

} )( $argv );