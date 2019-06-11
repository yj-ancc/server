<?php
// set the access control to the possible domain
header('Access-Control-Allow-Origin: *');
// Set the allow method
header('Access-Control-Allow-Methods:POST, GET');
// set the allow headers
header('Access-Control-Allow-Headers:x-requested-with,content-type');
// require library from stripe
require_once('./stripe-php/init.php');
// set the stripe Apikey
\Stripe\Stripe::setApiKey("sk_test_byJhMwJZ4jj85YuiZacgIjCV");
// Main function for collecting the informations
$post_data = file_get_contents("php://input");
// Decoding the json data to retrieve based on objects
$request = json_decode($post_data, true);
// get google pay token from the object of google pay
$token =  $request['id'];
try {
  // Use Stripe's library to make requests...
$charge = \Stripe\Charge::create([
    'amount' => 10,
    'currency' => 'aud',
    'description' => 'Example charge',
    'source' => $token,
]);
} catch(\Stripe\Error\Card $e) {
  // Since it's a decline, \Stripe\Error\Card will be caught
  $body = $e->getJsonBody();
  $err  = $body['error'];
  print('Status is:' . $e->getHttpStatus() . "\n");
  print('Type is:' . $err['type'] . "\n");
  print('Code is:' . $err['code'] . "\n");
  // param is '' in this case
  print('Param is:' . $err['param'] . "\n");
  print('Message is:' . $err['message'] . "\n");
} catch (\Stripe\Error\RateLimit $e) {
  // Too many requests made to the API too quickly
} catch (\Stripe\Error\InvalidRequest $e) {
  // Invalid parameters were supplied to Stripe's API
} catch (\Stripe\Error\Authentication $e) {
  // Authentication with Stripe's API failed
  // (maybe you changed API keys recently)
} catch (\Stripe\Error\ApiConnection $e) {
  // Network communication with Stripe failed
} catch (\Stripe\Error\Base $e) {
  // Display a very generic error to the user, and maybe send
  // yourself an email
} catch (Exception $e) {
  // Something else happened, completely unrelated to Stripe
}

?>
