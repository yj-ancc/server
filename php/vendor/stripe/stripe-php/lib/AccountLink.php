<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe;

/**
 * Class AccountLink
 *
 * @property string $object
 * @property int $created
 * @property int $expires_at
 * @property string $url
 *
 * @package Stripe
 */
class AccountLink extends ApiResource
{

    const OBJECT_NAME = "account_link";

    use ApiOperations\Create;
}
