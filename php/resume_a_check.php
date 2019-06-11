<?php
include 'names.php';
include 'login.php';
include 'help_resume_a_check.php';

header('Content-Type', 'application/json');

header('Access-Control-Allow-Origin: '.get_server_det());
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

// Database and Login information
$database_name = get_db_name();
$login_information =  '/'.get_login_file();

$post_data = file_get_contents("php://input");
// Decoding the json data to retrieve based on objects
$request = json_decode($post_data, true);

$debug = 0;
$ref_num = '';
$email = '';

if ($debug) {
  $ref_num = 'P5cf9bfbf8dd86';
  $email = 'savy.1712@gmail.com';
} else {
  $ref_num = $request['ref_num'];
  $email = $request['email'];
}
// establishing the DB connection
$con = get_connection_db($login_information, $database_name);

// get customer related information


if($con != NULL) {

  // getting the customer related information
  $customer_info = get_customer_information($con, $ref_num, $email, 'customer_info');

  // check for errors.
  // If none, proceed to next level
  // array structure :
  // first_name, middle_name, last_name, page_completed, started_date, phone_num, from_location
  if (array_key_exists('error', $customer_info)) {
    echo json_encode('customer information collection error');
    return;
  }
  // customer informations
  $first_name = $customer_info['first_name'];
  $middle_name = $customer_info['middle_name'];
  $last_name = $customer_info['last_name'];
  $phone_num = $customer_info['phone_num'];
  $started_date = $customer_info['started_date'];
  $from_location = $customer_info['from_location'];
  $is_single_name = $customer_info['is_single_name'];
  $page_completed = $customer_info['page_completed'];

  // getting the page number and proceeding with $page_number
  $page_number = get_page_number($con, $ref_num, $email, get_main_customer_table_name());
  if($page_number == 'end') {
    echo json_encode(array("page" => 'end'));
  } else if((int)$page_number > 2) {
      if($page_number ==  '3') {
        // payment page after successful transaction or not.
        // create a dependency on the successful payment details
        $main_details = get_payment_table_det($con, $ref_num, $email, 'payment', $customer_info);
        // echo json_encode($main_details);
        if($main_details['error'] != 'no') {
          // payment not done
          echo json_encode($main_details);
        } else {
          // echo ($main_details);
          // rows are fetched and informations could be extracted
          $type_of_check_abs = $main_details['type_of_check'];
          $payment_id  = $main_details['payment_id'];
          $payment_type = $main_details["payment_type"];
          $total_paid = $main_details["total_paid"];
          // with the payment id and type of check details forward onto particular detail to get the 'success'
          $payment_sec = get_payment_sec_info($con, $payment_id, 'payment_'.$payment_type, $customer_info, $main_details);
          echo json_encode($payment_sec);

        }

      } else if ($page_number == '4') {
        // info page is accessed

      } else if ($page_number == '5') {

      } else if ($page_number == '6') {


      } else if ($page_number == '7') {

      } else if ($page_number == '8') {

      } else if ($page_number == '9') {

      }

      // echo json_encode('success:'.$page_number);
  } else {
      echo json_encode(array("error" => "-1"));
  }
} else {
    echo json_encode(array( "error" => "connection not established"));
}








?>
