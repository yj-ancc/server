<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe;

/**
 * Class Token
 *
 * @property string $id
 * @property string $object
 * @property BankAccount $bank_account
 * @property Card $card
 * @property string $client_ip
 * @property int $created
 * @property bool $livemode
 * @property string $type
 * @property bool $used
 *
 * @package Stripe
 */
class Token extends ApiResource
{

    const OBJECT_NAME = "token";

    use ApiOperations\Create;
    use ApiOperations\Retrieve;
}
