<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe;

/**
 * Class FileLink
 *
 * @property string $id
 * @property string $object
 * @property int $created
 * @property bool $expired
 * @property int $expires_at
 * @property string $file
 * @property bool $livemode
 * @property StripeObject $metadata
 * @property string $url
 *
 * @package Stripe
 */
class FileLink extends ApiResource
{

    const OBJECT_NAME = "file_link";

    use ApiOperations\All;
    use ApiOperations\Create;
    use ApiOperations\Retrieve;
    use ApiOperations\Update;
}
