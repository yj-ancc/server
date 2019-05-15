<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


include_once 'login.php';
include_once 'names.php';

$debug = debug_check();

function update_information_fn($con, $table_name, $str_val, $val, $ref_num, $email) {
  $update_information = 'UPDATE '.$table_name.' '.$str_val;
  $update_information_cmd = $con->prepare($update_information);
  if($update_information_cmd != NULL) {
    /* update the page number bind params */
    $update_information_cmd->bind_param('sss', $val_t, $ref_num_t, $email_t);
    $val_t = $val;
    $ref_num_t = $ref_num;
    $email_t = $email;
  } else {
      if($debug) {
        echo json_encode('updation of page number command not prepared properly ');
      }
      return 0;
  }

  /* run the execution command for executing operation */
  $exec = $update_information_cmd->execute();
  if($exec) {
    $update_information_cmd->close();
    $exec = NULL;
    return 1;
  } else if($exec != NULL)  {
      if($debug) {
        echo json_encode('execution statement failure in page number updation');
      }
      $exec = NULL;
      return 0;
  } else {
      if($debug) {
        echo json_encode('execution failure inside the update information page');
      }
      return 0;
  }
  return -1;
}


$database_name = get_db_name();
$login_information =  '/'.get_login_file();

/* Retrieve all the contents from the info page for updating the values */
$post_data = file_get_contents("php://input");
/* Decoding the json data to retrieve based on objects */
$request = json_decode($post_data, true);

if(!$debug) {
  $ref_num = $request['ref_num'];
  $email = $request['email'];
  $page_completed = $request['page_completed'];
  $is_prev_name = $request['is_prev_name'];
  $type = $request['type'];
} else {
    $ref_num = '5c64e90c9ff57';
    $email = 'savy.1712@gmail.com';
    $page_completed = '6';
    $is_prev_name = 'yes';
    $type='pAndp';
}

if($ref_num == '' || $email == '' ) {
  echo json_encode('ns');
  return;
}

$fail_flag_prev = 0;
$fail_flag_page = 0;
$fail_flag = 0;

$con = get_connection_db($login_information, $database_name);

/* Checking if the connection is established */
if($con != NULL) {
  /* database connection is established */
  $update_page_number = 'SET page_completed=? WHERE reference_num=? AND email=?';
  $update_prev_name = 'SET is_prev_name=? WHERE reference_num=? AND email=?';

  if(update_information_fn($con, get_main_customer_table_name(), $update_page_number, $page_completed, $ref_num, $email)) {
    /* Once the page number is updated, then consider about the prev name addition */
    if( $type == 'page') {
      echo json_encode('success');
    } else if($type == 'pAndp') {
        if(update_information_fn($con, get_sec_customer_table_name(), $update_prev_name, $is_prev_name, $ref_num, $email)) {
          /* prev name addition is happening */
          echo json_encode('success');
        } else {
            $fail_flag_prev = 1;
        }
    } else {
        $fail_flag = 1;
    }
  } else {
        $fail_flag_page = 1;
  }

  if($fail_flag_page || $fail_flag_prev || $fail_flag ) {
    echo json_encode('ns');
  }

  /* close the established database connection */
  if($con != NULL) {
    $con->close();
  }

} else {
    if($debug) {
      echo json_encode('conn-fail');
    }
    echo json_encode('ns');

}

?>
