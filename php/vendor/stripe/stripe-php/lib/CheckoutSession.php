<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe;

/**
 * Class CheckoutSession
 *
 * @property string $id
 * @property string $object
 * @property bool $livemode
 *
 * @package Stripe
 */
class CheckoutSession extends ApiResource
{

    const OBJECT_NAME = "checkout_session";

    use ApiOperations\Create;
}
