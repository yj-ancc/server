<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe\Issuing;

/**
 * Class CardDetails
 *
 * @property string $id
 * @property string $object
 * @property Card $card
 * @property string $cvc
 * @property int $exp_month
 * @property int $exp_year
 * @property string $number
 *
 * @package Stripe\Issuing
 */
class CardDetails extends \Stripe\ApiResource
{
    const OBJECT_NAME = "issuing.card_details";
}
