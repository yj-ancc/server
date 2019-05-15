<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe\Radar;

/**
 * Class ValueList
 *
 * @property string $id
 * @property string $object
 * @property string $alias
 * @property int $created
 * @property string $created_by
 * @property string $item_type
 * @property Collection $list_items
 * @property bool $livemode
 * @property StripeObject $metadata
 * @property mixed $name
 * @property int $updated
 * @property string $updated_by
 *
 * @package Stripe\Radar
 */
class ValueList extends \Stripe\ApiResource
{
    const OBJECT_NAME = "radar.value_list";

    use \Stripe\ApiOperations\All;
    use \Stripe\ApiOperations\Create;
    use \Stripe\ApiOperations\Delete;
    use \Stripe\ApiOperations\Retrieve;
    use \Stripe\ApiOperations\Update;
}
