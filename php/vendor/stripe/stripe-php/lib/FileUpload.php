<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe;

// For backwards compatibility, the `File` class is aliased to `FileUpload`.
class_alias('Stripe\\File', 'Stripe\\FileUpload');
