<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe\Terminal;

/**
 * Class ConnectionToken
 *
 * @property string $secret
 *
 * @package Stripe\Terminal
 */
class ConnectionToken extends \Stripe\ApiResource
{
    const OBJECT_NAME = "terminal.connection_token";

    use \Stripe\ApiOperations\Create;
}
