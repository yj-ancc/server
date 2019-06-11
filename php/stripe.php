<?php

  include 'names.php';


    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
      header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
      header('Access-Control-Max-Age: 86400');    // cache for 1 day
  }

  // Access-Control headers are received during OPTIONS requests
  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
          header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
          header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

      exit(0);
  }


//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
//header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
require_once('vendor/stripe/stripe-php/init.php');


$key = get_stripe_key(get_login_path(), get_login_file());

if($key == NULL) return NULL;

$key  = ''.$key;
// echo json_encode($key);

// echo json_encode($key);
// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey($key);
// \Stripe\Stripe::setApiKey("sk_live_pzSeqW5dX26RHsCiz62br375");

// Token is created using Checkout or Elements!
// Get the payment token ID submitted by the form:

// Main function for collecting the informations
$post_data = file_get_contents("php://input");
// Decoding the json data to retrieve based on objects
$request = json_decode($post_data, true);
// customer object within the json is accessed in here
$customer_info =  $request['customer'];
$token = $customer_info['key'];
/*Toggling between the test and live production */
$amount = $customer_info['amount'];
/* converting from cents to dollars */
$amount = (double)$amount * 100;

try {
  // Use Stripe's library to make requests...
$charge = \Stripe\Charge::create([
    'amount' => $amount,
    'currency' => 'aud',
    'description' => 'Example charge',
    'source' => $token,
]);
echo json_encode($charge['status']);

} catch(\Stripe\Error\Card $e) {
  // Since it's a decline, \Stripe\Error\Card will be caught
  $body = $e->getJsonBody();
  $err  = $body['error'];

  echo json_encode('Status is:' . $e->getHttpStatus() . "\n");
  echo json_encode('Type is:' . $err['type'] . "\n");
  echo json_encode('Code is:' . $err['code'] . "\n");
  // param is '' in this case
  echo json_encode('Param is:' . $err['param'] . "\n");
  echo json_encode('Message is:' . $err['message'] . "\n");
} catch (\Stripe\Error\RateLimit $e) {
  // Too many requests made to the API too quickly
  $body = $e->getJsonBody();
    $err  = $body['error'];

    echo json_encode('Status is:' . $e->getHttpStatus() . "\n");
    echo json_encode('Type is:' . $err['type'] . "\n");
    echo json_encode('Code is:' . $err['code'] . "\n");
    // param is '' in this case
    echo json_encode('Param is:' . $err['param'] . "\n");
    echo json_encode('Message is:' . $err['message'] . "\n");
} catch (\Stripe\Error\InvalidRequest $e) {
  // Invalid parameters were supplied to Stripe's API
  $body = $e->getJsonBody();
    $err  = $body['error'];

    echo json_encode('Status is:' . $e->getHttpStatus() . "\n");
    echo json_encode('Type is:' . $err['type'] . "\n");
    echo json_encode('Code is:' . $err['code'] . "\n");
    // param is '' in this case
    echo json_encode('Param is:' . $err['param'] . "\n");
    echo json_encode('Message is:' . $err['message'] . "\n");
} catch (\Stripe\Error\Authentication $e) {
  // Authentication with Stripe's API failed
  // (maybe you changed API keys recently)
  $body = $e->getJsonBody();
    $err  = $body['error'];

    echo json_encode('Status is:' . $e->getHttpStatus() . "\n");
    echo json_encode('Type is:' . $err['type'] . "\n");
    echo json_encode('Code is:' . $err['code'] . "\n");
    // param is '' in this case
    echo json_encode('Param is:' . $err['param'] . "\n");
    echo json_encode('Message is:' . $err['message'] . "\n");
} catch (\Stripe\Error\ApiConnection $e) {
  // Network communication with Stripe failed
  $body = $e->getJsonBody();
    $err  = $body['error'];

    echo json_encode('Status is:' . $e->getHttpStatus() . "\n");
    echo json_encode('Type is:' . $err['type'] . "\n");
    echo json_encode('Code is:' . $err['code'] . "\n");
    // param is '' in this case
    echo json_encode('Param is:' . $err['param'] . "\n");
    echo json_encode('Message is:' . $err['message'] . "\n");
} catch (\Stripe\Error\Base $e) {
  // Display a very generic error to the user, and maybe send
  // yourself an email
  $body = $e->getJsonBody();
    $err  = $body['error'];

    echo json_encode('Status is:' . $e->getHttpStatus() . "\n");
    echo json_encode('Type is:' . $err['type'] . "\n");
    echo json_encode('Code is:' . $err['code'] . "\n");
    // param is '' in this case
    echo json_encode('Param is:' . $err['param'] . "\n");
    echo json_encode('Message is:' . $err['message'] . "\n");
} catch (Exception $e) {
  // Something else happened, completely unrelated to Stripe
  $body = $e->getJsonBody();
    $err  = $body['error'];

    echo json_encode('Status is:' . $e->getHttpStatus() . "\n");
    echo json_encode('Type is:' . $err['type'] . "\n");
    echo json_encode('Code is:' . $err['code'] . "\n");
    // param is '' in this case
    echo json_encode('Param is:' . $err['param'] . "\n");
    echo json_encode('Message is:' . $err['message'] . "\n");
}

?>
