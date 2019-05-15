<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe\Util;

/**
 * A very basic implementation of LoggerInterface that has just enough
 * functionality that it can be the default for this library.
 */
class DefaultLogger implements LoggerInterface
{
    public function error($message, array $context = [])
    {
        if (count($context) > 0) {
            throw new \Exception('DefaultLogger does not currently implement context. Please implement if you need it.');
        }
        error_log($message);
    }
}
