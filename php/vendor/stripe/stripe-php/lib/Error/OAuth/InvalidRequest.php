<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe\Error\OAuth;

/**
 * InvalidRequest is raised when a code, refresh token, or grant type
 * parameter is not provided, but was required.
 */
class InvalidRequest extends OAuthBase
{
}
