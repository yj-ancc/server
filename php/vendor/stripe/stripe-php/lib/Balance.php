<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe;

/**
 * Class Balance
 *
 * @property string $object
 * @property array $available
 * @property array $connect_reserved
 * @property bool $livemode
 * @property array $pending
 *
 * @package Stripe
 */
class Balance extends SingletonApiResource
{

    const OBJECT_NAME = "balance";

    /**
     * @param array|string|null $opts
     *
     * @return Balance
     */
    public static function retrieve($opts = null)
    {
        return self::_singletonRetrieve($opts);
    }
}
