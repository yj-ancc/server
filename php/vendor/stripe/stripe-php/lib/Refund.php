<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe;

/**
 * Class Refund
 *
 * @property string $id
 * @property string $object
 * @property int $amount
 * @property string $balance_transaction
 * @property string $charge
 * @property int $created
 * @property string $currency
 * @property string $failure_balance_transaction
 * @property string $failure_reason
 * @property StripeObject $metadata
 * @property string $reason
 * @property string $receipt_number
 * @property string $source_transfer_reversal
 * @property string $status
 *
 * @package Stripe
 */
class Refund extends ApiResource
{

    const OBJECT_NAME = "refund";

    use ApiOperations\All;
    use ApiOperations\Create;
    use ApiOperations\Retrieve;
    use ApiOperations\Update;
}
