<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe\Error\OAuth;

/**
 * UnsupportedGrantType is raised when an unuspported grant type
 * parameter is specified.
 */
class UnsupportedGrantType extends OAuthBase
{
}
