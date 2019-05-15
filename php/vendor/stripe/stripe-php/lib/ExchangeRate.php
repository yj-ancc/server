<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe;

/**
 * Class ExchangeRate
 *
 * @package Stripe
 */
class ExchangeRate extends ApiResource
{

    const OBJECT_NAME = "exchange_rate";

    use ApiOperations\All;
    use ApiOperations\Retrieve;
}
