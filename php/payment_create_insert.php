<?php
header('Access-Control-Allow-Origin: '.get_server_det());
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
include 'normalised_create_insert.php';

/* payment create and insert command : create and insert values into the payment table */
function payment_create($con, $data, $params, $types, $table_name, $primary_key, $foreign_key) {

  /* Checking if the data and params are matching */
  $combined_param = '';
  $comma = ',';
  $combined_param = check_attributes($data, $params, $types, $comma, $primary_key, $foreign_key, 0);
  if($combined_param != '') {
    /* If the parameter is found, then combined param will not be empty and filled with data types and variable names */
    /* create table query */
    $create_query = "CREATE TABLE IF NOT EXISTS  ".$table_name. ' ('.$combined_param.' )';
    if($con->query($create_query)) {
      /* Creating the Payment Main table */
      return 1;
    } else {
        return 0;
      }
  } else {
      /* returning false when the attributes count doesn't match */
      return 0;
  }
}

function check_hard($type) {
  $type_list = explode("-", $type);
  // format : i-pbvh-e
  if (count($type_list) == 3) {
    if (strpos($type_list[1], 'h') !== false) {
        return 1;
    }
  }
  return 0;
}

function customer_update_information($con, $table_name, $str_val, $val, $ref_num, $email, $hard_copy) {
  $update_information = 'UPDATE '.$table_name.' '.$str_val;
  $update_information_cmd = $con->prepare($update_information);
  if($update_information_cmd != NULL) {
    /* update the page number bind params */
    $update_information_cmd->bind_param('ssss', $val_t, $hard_copy_t, $ref_num_t, $email_t);
    $val_t = $val;
    $hard_copy_t = $hard_copy;
    $ref_num_t = $ref_num;
    $email_t = $email;
  } else {
      if($debug) {
        echo json_encode('updation of type of check not working properly ');
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
        echo json_encode('execution statement failure in check type ');
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



function payment_insert($con, $table_name, $data) {
  $insert_prepare = '';
  if( $table_name == get_payment_table_name()) {
    $insert_prepare = $con->prepare ('INSERT INTO'.' '. $table_name.' ( reference_num, email, payment_type, type_of_check, payment_id, card_count, paypal_count, gpay_count, apay_count, total_paid) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $insert_prepare->bind_param("sssssiiiid", $ref_num, $email, $payment_type, $type_of_check, $payment_id, $card_count, $paypal_count, $gpay_count, $apay_count, $total_paid );
    $ref_num = $data[0];
    $email = $data[1];
    $payment_type = $data[2];
    $type_of_check = $data[3];
    $payment_id = $data[4];
    $card_count = $data[5];
    $paypal_count = $data[6];
    $gpay_count = $data[7];
    $apay_count = $data[8];
    $total_paid = $data[9];
  } else if ($table_name == get_payment_card_table_name()) {
      $insert_prepare = $con->prepare('INSERT INTO '.' '.$table_name.' (payment_id, payment_status, payment_start_time, payment_end_time, last_4, card_type, processing_fee) VALUES ( ?, ?, ?, ?, ?, ?, ?)');
      $insert_prepare->bind_param("ssssisd", $payment_id,  $payment_status, $payment_start_time, $payment_end_time, $last_4, $card_type, $processing_fee );
      $payment_id = $data[0];
      $payment_status = $data[1];
      $payment_start_time = $data[2];
      $payment_end_time = $data[3];
      $last_4 = $data[4];
      $card_type = $data[5];
      $processing_fee = $data[6];
  } else if($table_name == get_payment_paypal_table_name()) {
          $insert_prepare = $con->prepare('INSERT INTO '.' '.$table_name.' (payment_id, payment_status, payment_start_time, payment_end_time) VALUES (?, ?, ?, ?)');
          $insert_prepare->bind_param("ssss", $payment_id,  $payment_status, $payment_start_time, $payment_end_time);
          $payment_id = $data[0];
          $payment_status= $data[1];
          $payment_start_time = $data[2];
          $payment_end_time = $data[3];
  } else {
      return 0;
  }

  /* Run the execution of the insert command */
  if ($insert_prepare != '' && $insert_prepare->execute()) {
      $insert_prepare->close();
      return 1;
  } else {
      if ($insert_prepare != '') {
        $insert_prepare->close();
      }
      return 0;
  }

}

?>
