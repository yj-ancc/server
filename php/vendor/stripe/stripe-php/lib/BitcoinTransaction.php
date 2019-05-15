<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe;

/**
 * Class BitcoinTransaction
 *
 * @package Stripe
 */
class BitcoinTransaction extends ApiResource
{

    const OBJECT_NAME = "bitcoin_transaction";
}
