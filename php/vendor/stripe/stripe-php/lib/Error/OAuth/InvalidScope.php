<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe\Error\OAuth;

/**
 * InvalidScope is raised when an invalid scope parameter is provided.
 */
class InvalidScope extends OAuthBase
{
}
