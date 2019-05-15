<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe;

/**
 * Class WebhookEndpoint
 *
 * @property string $id
 * @property string $object
 * @property int $created
 * @property string[] $enabled_events
 * @property bool $livemode
 * @property string $secret
 * @property string $status
 * @property string $url
 *
 * @package Stripe
 */
class WebhookEndpoint extends ApiResource
{

    const OBJECT_NAME = "webhook_endpoint";

    use ApiOperations\All;
    use ApiOperations\Create;
    use ApiOperations\Delete;
    use ApiOperations\Retrieve;
    use ApiOperations\Update;
}
