<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe\Error;

class SignatureVerification extends Base
{
    public function __construct(
        $message,
        $sigHeader,
        $httpBody = null
    ) {
        parent::__construct($message, null, $httpBody, null, null);
        $this->sigHeader = $sigHeader;
    }

    public function getSigHeader()
    {
        return $this->sigHeader;
    }
}
