<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe\Terminal;

/**
 * Class Reader
 *
 * @property string $id
 * @property string $object
 * @property string $device_type
 * @property string $serial_number
 * @property string $label
 * @property string $ip_address
 *
 * @package Stripe\Terminal
 */
class Reader extends \Stripe\ApiResource
{
    const OBJECT_NAME = "terminal.reader";

    use \Stripe\ApiOperations\All;
    use \Stripe\ApiOperations\Create;
    use \Stripe\ApiOperations\Retrieve;
    use \Stripe\ApiOperations\Update;
}
