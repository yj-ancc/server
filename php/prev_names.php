<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
include_once 'login.php';
include_once 'names.php';
include_once 'customer_address_create_insert.php';

$debug = debug_check();

$database_name = get_db_name();
$login_information =  '/'.get_login_file();

/* Retrieve all the contents from the info page for updating the values */
$post_data = file_get_contents("php://input");
/* Decoding the json data to retrieve based on objects */
$request = json_decode($post_data, true);

if($debug) {
  $ref_num = '5c64e90c9ff57';
  $email = 'savy.1712@gmail.com';
  $otherNames = array(array('nameType'=>'Maiden', 'previous_first_name'=>'SS', 'previous_middle_name'=>'', 'previous_last_name'=>'AA', 'is_single_name'=>'no'), array('nameType'=>'Maiden', 'previous_first_name'=>'SS', 'previous_middle_name'=>'', 'previous_last_name'=>'AA', 'is_single_name'=>'no'));
} else {
    $ref_num = $request['ref_num'];
    $email = $request['email'];
    $otherNames = $request['prev_names'];
}

$var_len = get_var_len();
$comma = ',';

$con = get_connection_db($login_information, $database_name);

/* Checking if the connection is established */
if($con != NULL) {

  $table_name = get_customer_previous_name();
  $create_types = array('VARCHAR('.$var_len.')',
                        'VARCHAR('.$var_len.')',
                        'VARCHAR('.$var_len.')',
                        'VARCHAR('.$var_len.') NOT NULL',
                        'VARCHAR('.$var_len.') NOT NULL',
                        'VARCHAR('.$var_len.') ',
                        'VARCHAR('.$var_len.') ',
                        'VARCHAR('.$var_len.') '
                        );

  $create_params = array ('reference_num', 'email', 'pid', 'name_type', 'first_name', 'middle_name', 'last_name', 'is_single_name');
  $create_data = array();
  for($i = 0; $i < count($otherNames); $i++) {
    $create_data[$i] = array($ref_num, $email, $i, $otherNames[$i]['nameType'], $otherNames[$i]['previous_first_name'],
                      $otherNames[$i]['previous_middle_name'], $otherNames[$i]['previous_last_name'], $otherNames[$i]['is_single_name']);
  }
  $failure_flag = 0;
  $primary_key = ' PRIMARY KEY(pid) ';
  $foreign_key = ' FOREIGN KEY (reference_num, email) REFERENCES '.get_sec_customer_table_name().'(reference_num, email) ON UPDATE CASCADE ';
  $create_status = create_operations($con, $table_name, $create_data[0], $create_params, $create_types, $comma, $primary_key, $foreign_key);
  if($create_status == 'success') {
    if(count($create_data) < 1) {
      echo json_encode($create_status);
      if($con != NULL) {
        $con->close();
      }
      return;
    }
    /* create status is true */
    /* insert the value into the previous name DB */
    for($i=0; $i< count($create_data);$i++) {
      $insert_status = insert_query_address($con, $table_name, $create_data[$i], $create_params, $i);
      // echo json_encode($insert_status);
      if($insert_status != 'success') {
        $failure_flag = 1;
        break;
      }
    }
    if(!$failure_flag) {
      echo json_encode($insert_status);
    } else {
        echo json_encode('ins-fail');
    }
  } else {
      echo json_encode('ns');
  }
} else {
    if($debug) {
      echo json_encode('conn-fail-prev');
    }
    echo json_encode('ns:conn-fail');
}

if($con != NULL) {
  $con->close();
}

?>

