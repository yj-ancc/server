<?php
header('Content-Type', 'application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

include 'names.php';
include 'login.php';
include 'fetch.php';

$post_data = file_get_contents("php://input");
$request = json_decode($post_data, true);
$ref_num = $request['ref_num'];

/* Main purpose of adding the database : updating the details */
/* Establishing the connection with the database to retrieve the information */
if(($con = get_connection_db(get_login_file(), get_db_name())) != NULL ) {
  if ( $cust_info = fetch_table_customer($con, $ref_num) ) {
      $result_string = $cust_info;
  } else {
      echo json_encode('customer table details not fetched properly!');
  }

  if($purp_info = fetch_table_purp($con, $ref_num)) {
      $result_string .= '&'.$purp_info;
      echo json_encode($result_string);
      $con->close();


  } else {
      echo json_encode('purpose of check table details not fetched properly! ');
  }

} else {
    echo json_encode('Connection failure inside retrieve_info.php');
}
?>
