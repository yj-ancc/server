<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


include_once 'names.php';
include_once 'login.php';
include_once 'customer_address_create_insert.php';

$debug =  debug_check();

/* Getting the database name */
$database_name = get_db_name();
/* Getting the login file information */
$login_information =  '/'.get_login_file();

/* Retrieve all the contents from the info page for updating the values */
$post_data = file_get_contents("php://input");

/* Decoding the json data to retrieve based on objects */
$request = json_decode($post_data, true);

$delete_previous_name_flag = 0;

if($debug) {
    $ref_num = '5c64e90c9ff57';
    $email = 'savy.1712@gmail.com';
    $prev_names = array(array('nameType'=>'Maiden', 'previous_first_name'=>'SS', 'previous_middle_name'=>'', 'previous_last_name'=>'AA', 'is_single_name'=>'no'), array('nameType'=>'Maiden', 'previous_first_name'=>'SS', 'previous_middle_name'=>'', 'previous_last_name'=>'AA', 'is_single_name'=>'no'));
    $from_info4 = 1;

} else {
  $ref_num = $request['ref_num'];
  $email = $request['email'];
  $prev_names = $request['previous_names'];
  $from_info4 = $request['from_info4'];
}

$insert_status = '';
$failure_flag = 0;
$delete_failure_flag = 0;

if(!$ref_num || !$email) {
  echo json_encode('ns: reference number or email not succeeding ');
  if($debug) {
    echo json_encode(" reference number or email is not present ");
  }
  return;

}

if($from_info4) {

  /* Establishing the database connection */
  $con = get_connection_db($login_information, $database_name);

  /* Delete the previous name list based on the reference number and email  */
  $delete_previous_name_flag = delete_record($con, get_customer_previous_name(), $ref_num, $email);

  if($delete_previous_name_flag) {
    $create_data = array();
    for($i = 0; $i < count($prev_names); $i++) {
      $create_data[$i] = array($ref_num, $email, $i, $prev_names[$i]['nameType'], $prev_names[$i]['previous_first_name'],
                               $prev_names[$i]['previous_middle_name'], $prev_names[$i]['previous_last_name'], $prev_names[$i]['is_single_name']);
    }
    /* inserting the new elements into the list */
    $create_params = array ('reference_num', 'email', 'pid', 'name_type', 'first_name', 'middle_name', 'last_name', 'is_single_name');
    for($i=0; $i < count($create_data); $i++) {
      $insert_status = insert_query_address($con, get_customer_previous_name(), $create_data[$i], $create_params, $i);
      if($insert_status != 'success') {
        $failure_flag = 1;
        break;
      }
    }

    if($failure_flag) {
      echo json_encode('ns-insert-prev-name');
      if($debug) {
        echo json_encode('previous name inserting error');
      }
    } else if( $delete_failure_flag) {
        echo json_encode('ns-delete-not-done');
        if($debug){
          echo json_encode('delete not happening');
        }
    } else {
        echo json_encode('success');
    }

  } else {
      echo json_encode('ns');
      if($debug) {
        echo json_encode(' deletion of records didnot happen properly ');
      }
  }

  /*  Connection establishment deactivation procedure */
  if($con != NULL) {
    $con->close();
  }

} else {
    echo json_encode('ns');
    if($debug) {
      echo json_encode('not succeeding : info4 flag is not initialised ');
    }
}




