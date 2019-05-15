<?php

include_once 'names.php';
include_once 'payment_create_insert.php';
include_once 'login.php';




// Main function for collecting the informations
$post_data = file_get_contents("php://input");
// Decoding the json data to retrieve based on objects
$request = json_decode($post_data, true);

$debug = debug_check();
/* customer object within the json is accessed in here */
/* Retrieving all the informations necessary for the payment information table design */
if(!$debug) {
$ref_num = $request['ref_num'];
$email = $request['email'];
$payment_method = $request['payment_method'];
$payment_status = $request['status'];
$type_of_check = $request['type_check'];
$payment_id = $request['payment_id'];
$card_brand = $request['card_brand'];
$payment_start_time = $request['payment_initiated_time'];
$payment_end_time = $request['payment_finished_time'];
$total_paid = $request['total_amount'];
$last_4 = $request['last_4'];
$fee = $request['fee'];
} else {
$ref_num = '5c3ffbafd76a1'; //$request['ref_num'];
$email = 'savy.1712@gmail.com '; //$request['email'];
$payment_method = 'card' ;//$request['payment_method'];
$payment_status = 'success'; //$request['status'];
$type_of_check = 'i-p-e'; //$request['type_check'];
$payment_id = 'P_'. $ref_num;// $request['payment_id'];
$card_brand = 'visa' ;//$request['card_brand'];
$payment_start_time ='20/01/2019, 13:32:00' ;// $request['payment_initiated_time'];
$payment_end_time = '20/01/2019, 13:32:15' ;// $request['payment_finished_time'];
$total_paid = '111' ; // $request['total_amount'];
$last_4 = '4242'; //$request['last_4'];
$fee = '0.69' ;// $request['fee'];
}

$var_char_len = get_var_len();
$main_success = 0;
$check_flag = 0;

/* Variables to keep track of type of transaction */
$cc = 0;
$gc = 0;
$pc = 0;
$ac = 0;

/* Database connection and access information */
$database_name = get_db_name();
$login_information = '/'. get_login_file();

/* Establishing the database connection */
if(($con = get_connection_db($login_information, $database_name))!= NULL ) {
    /* Select the main payment table and check its limit to 1 */
    /* Find its presence. If not present, create a new table with all counts initialised to zero */
    $check_payment_main_table = 'select * from  `'.get_payment_table_name().'` LIMIT 1';
    $table_name = get_payment_table_name();


    /* Executing the conditions for checking the existence of payment table */
    if($con->query($check_payment_main_table) != TRUE ) {
      if($payment_method == get_card_name()) $cc  = 1;
      else if($payment_method == get_paypal_name()) $pc = 1;
      else if($payment_method == get_gpay_name()) $gc = 1;
      else if($payment_method == get_apay_name()) $ac = 1;

      $data_main = array($ref_num, $email, $payment_method, $type_of_check, $payment_id, $cc, $pc, $gc, $ac, $total_paid );
      $params_main = array( 'reference_num', 'email', 'payment_type', 'type_of_check','payment_id', 'card_count', 'paypal_count', 'gpay_count', 'apay_count', 'total_paid');
      $types_main = array('VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')',
                          'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')', 'INT',
                          'INT', 'INT', 'INT', 'DOUBLE');

      $primary_key = 'PRIMARY KEY(payment_id, reference_num, email)';
      $foreign_key = 'FOREIGN KEY(reference_num, email) REFERENCES '.get_main_customer_table_name().'(reference_num, email)';

      /* creating the main payment table */
      $payment_store = payment_create($con, $data_main, $params_main, $types_main, $table_name, $primary_key, $foreign_key);
      if(! $payment_store ) {
        echo json_encode('nc-pay_main');
        return;
      } else {
          /* Insert with the basic values */
          $payment_main = payment_insert($con, $table_name, $data_main);
          if(!$payment_main) {
            echo json_encode('ni-pay_main');
          } else {
              $main_success = 1;
              /* After the main payment table is created */
              /* Update the customer main table with the check type */
              $str_val = 'SET type_of_check=? WHERE reference_num=? AND email=?';
              $update_check_type = customer_update_information($con, get_main_customer_table_name(), $str_val, $type_of_check, $ref_num, $email);
              if($update_check_type) {
                $check_flag = 1;
              }
          }

      }
    } else  {
      $retrieve_counts = "Select max(card_count) as cc, max(paypal_count) as pc, max(gpay_count) as gc, max(apay_count) as ac FROM ".get_payment_table_name();
      $result = $con->query($retrieve_counts);
      if($result) {
        $row = mysqli_fetch_assoc($result);
        if($payment_method == get_card_name()) {
          $cc = ((int) $row['cc']) + 1;
          $pc = ((int) $row['pc']);
          $gc = ((int) $row['gc']);
          $ac = ((int) $row['ac']);
        } else if($payment_method == get_paypal_name()) {
            $cc = ((int) $row['cc']);
            $pc = ((int) $row['pc']) + 1;
            $gc = ((int) $row['gc']);
            $ac = ((int) $row['ac']);
        } else if($payment_method == get_gpay_name()) {
            $cc = ((int) $row['cc']);
            $pc = ((int) $row['pc']);
            $gc = ((int) $row['gc']) + 1;
            $ac = ((int) $row['ac']);
        } else if($payment_method == get_apay_name()) {
            $cc = ((int) $row['cc']);
            $pc = ((int) $row['pc']);
            $gc = ((int) $row['gc']);
            $ac = ((int) $row['ac']) + 1;
        }
        /* Inserting element into the database */
        $data_main = array($ref_num, $email, $payment_method, $type_of_check, $payment_id, $cc, $pc, $gc, $ac, $total_paid);
        $payment_main = payment_insert($con, $table_name, $data_main);
        echo json_encode($data_main);
        if(!$payment_main) {
          echo json_encode('ni-pay_main');
        } else {
            $main_success = 1;
            $str_val = 'SET type_of_check=? WHERE reference_num=? AND email=?';
            $update_check_type = customer_update_information($con, get_main_customer_table_name(), $str_val, $type_of_check, $ref_num, $email);
            if($update_check_type) {
              $check_flag = 1;
            }
        }
      }
    }

    /* If the main payment table is created, then create the payment_card and payment_paypal table */
    if( $main_success ) {
      if( $payment_method == get_card_name()) {
      /* pay by card table */
        $data_card = array($payment_id, $payment_status, $payment_start_time, $payment_end_time, $last_4, $card_brand, $fee );
        $params_card = array('payment_id', 'payment_status', 'payment_start_time', 'payment_end_time', 'last_4', 'card_type', 'processing_fee');
        $types_card = array('VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')',
                            'VARCHAR('.$var_char_len.')', 'INT', 'VARCHAR('.$var_char_len.')', 'DOUBLE');
        $primary_key_card = 'PRIMARY KEY(payment_id)';
        $foreign_key_card = 'FOREIGN KEY(payment_id) REFERENCES '.get_payment_table_name().'(payment_id)';
        $table_card = get_payment_card_table_name();

        /* creating the card table procedure */
        $payment_store_card = payment_create($con, $data_card, $params_card, $types_card, $table_card, $primary_key_card, $foreign_key_card);
        if(! $payment_store_card ) {
          echo 'nc-pay_card';
          return;
        } else {
            $payment_card = payment_insert($con, $table_card, $data_card);
            if(!$payment_card) {
              echo json_encode('ni-pay_card');
            } else {
                $card_table_created = 1;
            }
        }
      } else if ($payment_method == get_paypal_name())  {
        /* payment by paypal */
        $data_paypal = array($payment_id, $payment_status, $payment_start_time, $payment_end_time);
        $params_paypal = array('payment_id', 'payment_status', 'payment_start_time', 'payment_end_time');
        $types_paypal = array('VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')',
                            'VARCHAR('.$var_char_len.')' );
        $primary_key_paypal = 'PRIMARY KEY(payment_id)';
        $foreign_key_paypal = 'FOREIGN KEY(payment_id) REFERENCES '.get_payment_table_name().'(payment_id)';
        $table_paypal = get_payment_paypal_table_name();

        $payment_store_paypal = payment_create($con, $data_paypal, $params_paypal, $types_paypal, $table_paypal, $primary_key_paypal, $foreign_key_paypal);
        if(! $payment_store_paypal ) {
          echo 'nc-pay_card';
          $con->close();
          return;
        } else {
            $payment_paypal = payment_insert($con, $table_paypal, $data_paypal);
            if(!$payment_paypal) {
              echo json_encode('ni-pay_paypal');
              $con->close();
            } else {
                $payment_table_created = 1;
                if($con != NULL) {
                  $con->close();
                }
            }
        }
      }
    } else {
        echo json_encode('nc-pay-main');
        $con->close();
    }
} else {
    echo json_encode('no-conn-pay-main');
}


?>
