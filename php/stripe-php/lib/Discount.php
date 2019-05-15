<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe;

/**
 * Class Discount
 *
 * @property string $object
 * @property Coupon $coupon
 * @property string $customer
 * @property int $end
 * @property int $start
 * @property string $subscription
 *
 * @package Stripe
 */
class Discount extends StripeObject
{

    const OBJECT_NAME = "discount";
}
