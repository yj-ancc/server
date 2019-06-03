<?php
header('Content-Type', 'application/json');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
include 'login.php';
include 'names.php';
include 'normalised_create_insert.php';

$database_name = get_db_name();
$login_information =  '/'.get_login_file();


$post_data = file_get_contents("php://input");
// Decoding the json data to retrieve based on objects
$request = json_decode($post_data, true);

$debug = 0;


if ($debug) {
  $ref_num = 'P5cf21228ae7a3';
  $type_check = 'i-p-e';
  $last_logged = '2019-02-20 14:29:04';
} else {
  $ref_num = $request['ref_num'];
  $type_check = $request['type_of_check'];
  $last_logged = $request['last_logged'];
}

/*
  type of check params
 * i = individual
 * p = police checks
 * b = bank checks
 * ve= vevo checks
 * e =  employment
 * vo = voluntary checks
*/

$first_name = '';
$middle_name = '';
$last_name = '';
$email = '';
$phone_num = '';
$page_completed = '';
$started_date = '';
$from_location = '';
$is_single_name = '';
$var_char_len = get_var_len();
$flag = 0;
/* updating the previous name category to be NO by default */
$is_prev_name = 'no';

$con = get_connection_db($login_information, $database_name);

if($con != NULL) {
  /* Connection is established with the database */
  /* Fetch all the informations required to create the normalised tables */
  $select_query = "SELECT  first_name, middle_name, last_name, email, phone_num, email, page_completed, started_date, from_location, is_single_name FROM ".get_customer_table_name()." WHERE reference_num='".$ref_num."'";

  /* Executing the select statement : checking the working of select statement */
  $result = $con->query($select_query);
  $result_string = '';
  $invoice = 0;
  /* Checking for the execution status of the select statement */
  if($result == FALSE) {
      echo json_encode('executing select statement failure inside customer_normalise.php');
      return;
  } else if ($result) {
     /* If the select statement is executed, the retrieve the email and phone number */
     $cr = 0;
     $row = mysqli_fetch_assoc($result);
     if($row) {
          /* All the informations from the customer page is extracted */
          $first_name = $row['first_name'];
          $middle_name = $row['middle_name'];
          $last_name = $row['last_name'];
          $email = $row['email'];
          $phone_num = $row['phone_num'];
          $page_completed = get_payment_page_number();
          $started_date = $row['started_date'];
          $from_location = $row['from_location'];
          $application_status = '2';
          $hard_copy_requested = '0';
          if( $last_name == '') {
            $is_single_name = '1';
          }
          $verification_status = 'success';

          $invoice = get_invoice_val();
          /* Creating the customer main table */
          $data = array($ref_num, $email, $phone_num, $type_check, $page_completed, $started_date, NULL, $invoice, $application_status, $hard_copy_requested);
          $params = array ('reference_num', 'email', 'phone_num', 'type_of_check', 'page_completed', 'started_date', 'submitted_date', 'invoice_num', 'application_status', 'hard_copy_requested');
          $types = array( 'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')',
                          'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')', 'DATETIME',
                          'DATETIME', 'INT', 'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')');

          $table_name = get_main_customer_table_name();
          $primary_key = 'PRIMARY KEY(reference_num, email)';
          $foreign_key = '';
          $create = create_insert_table($con, $data, $params, $table_name, $types, $primary_key, $foreign_key, $invoice);
          // echo json_encode($create);
          if($create > 0) {
             /* creation of table and updation into the tables is success */

             /* Creating the customer secondary main table */
             $data = array($ref_num, $first_name, $middle_name, $last_name, $email, $is_single_name, $last_logged, $from_location, '', '', '', '', '', $is_prev_name);
             $params = array ('reference_num', 'first_name', 'middle_name', 'last_name', 'email', 'is_single_name', 'last_logged', 'from_location', 'dob', 'gender', 'state_born', 'suburb_born', 'country_born', 'is_prev_name');
             $types = array( 'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')',
                             'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')',
                             'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')','VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')',
                             'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')' );
             $primary_key = 'PRIMARY KEY(reference_num, email)';
             $foreign_key = 'FOREIGN KEY(reference_num, email) REFERENCES '.get_main_customer_table_name().'(reference_num, email) ON UPDATE CASCADE';
             $table_name = get_sec_customer_table_name();

             /* Naming the secondary customer table information */
             $create = create_insert_table($con, $data, $params, $table_name, $types, $primary_key, $foreign_key, $create);
             // echo json_encode($create);
             if($create > 0) {
                // check for rfi and application table existence and insert values
                if (check_table_exists($con, get_rfi_table())) {
                  // check for the application table and insert values accordingly
                  if(check_table_exists($con, get_application_date_table())) {
                    // inserting values into the application_dates table
                    $insert_command = insert_values($con, get_application_date_table(), $ref_num);
                    // insert command succeeds
                    if($insert_command == 1) {
                      // inserting into the rfi table
                      $insert_command = insert_values($con, get_rfi_table(), $ref_num);
                      if ($insert_command == 1) {
                       echo json_encode('success:'.''.$create);
                      } else {
                          echo json_encode('insert failed in rfi table');
                      }
                    } else {
                        echo json_encode('insert failed in application_dates');
                    }
                    // echo json_encode('success:'.''.$create);
                  } else {
                      echo json_encode('application table missing');
                      return;
                  }
                } else {
                    echo json_encode('Rfi table missing');
                    return;
                }
                $con->close();

              } else {
                  /* catch all the exceptions for the json encode function */
                  $flag = 1;
                }
          } else {
              /* customer_main table failed to create */
              echo json_encode('ns:ns-main');
              $flag = 0;
              $con->close();
          }
          /* if the customer secondary fails but not the customer_main table */
          if($flag) {
            echo json_encode('ns:ns-sec');
            $con->close();
          }
        } else {
          echo json_encode('ns:row fetch issue');
          $con->close();
      }
  }
} else {
  echo json_encode('ns:connection not established');
  // $con->close();
}

?>

