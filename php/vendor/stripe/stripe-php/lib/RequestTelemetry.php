<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe;

/**
 * Class RequestTelemetry
 *
 * Tracks client request telemetry
 * @package Stripe
 */
class RequestTelemetry
{
    public $requestId;
    public $requestDuration;

    /**
     * Initialize a new telemetry object.
     *
     * @param string $requestId The request's request ID.
     * @param int $requestDuration The request's duration in milliseconds.
     */
    public function __construct($requestId, $requestDuration)
    {
        $this->requestId = $requestId;
        $this->requestDuration = $requestDuration;
    }
}
