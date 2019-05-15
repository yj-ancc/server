<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe;

/**
 * Class Coupon
 *
 * @property string $id
 * @property string $object
 * @property int $amount_off
 * @property int $created
 * @property string $currency
 * @property string $duration
 * @property int $duration_in_months
 * @property bool $livemode
 * @property int $max_redemptions
 * @property StripeObject $metadata
 * @property string $name
 * @property int $percent_off
 * @property int $redeem_by
 * @property int $times_redeemed
 * @property bool $valid
 *
 * @package Stripe
 */
class Coupon extends ApiResource
{

    const OBJECT_NAME = "coupon";

    use ApiOperations\All;
    use ApiOperations\Create;
    use ApiOperations\Delete;
    use ApiOperations\Retrieve;
    use ApiOperations\Update;
}
