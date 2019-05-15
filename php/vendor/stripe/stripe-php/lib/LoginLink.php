<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe;

/**
 * Class LoginLink
 *
 * @property string $object
 * @property int $created
 * @property string $url
 *
 * @package Stripe
 */
class LoginLink extends ApiResource
{

    const OBJECT_NAME = "login_link";
}
