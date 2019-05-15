<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe;

/**
 * Class SourceTransaction
 *
 * @property string $id
 * @property string $object
 * @property int $amount
 * @property int $created
 * @property string $customer_data
 * @property string $currency
 * @property string $type
 * @property mixed $ach_credit_transfer
 *
 * @package Stripe
 */
class SourceTransaction extends ApiResource
{

    const OBJECT_NAME = "source_transaction";
}
