<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe\Reporting;

/**
 * Class ReportRun
 *
 * @property string $id
 * @property string $object
 * @property int $created
 * @property string $error
 * @property bool $livemode
 * @property mixed $parameters
 * @property string $report_type
 * @property mixed $result
 * @property string $status
 * @property int $succeeded_at
 *
 * @package Stripe\Reporting
 */
class ReportRun extends \Stripe\ApiResource
{
    const OBJECT_NAME = "reporting.report_run";

    use \Stripe\ApiOperations\All;
    use \Stripe\ApiOperations\Create;
    use \Stripe\ApiOperations\Retrieve;
}
