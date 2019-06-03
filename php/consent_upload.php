<?php
header('Content-Type', 'application/json');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
include 'login.php';
include 'names.php';

$database_name = get_db_name();
$login_information =  '/'.get_login_file();

// collecting all the required php input streams
$post_data = file_get_contents("php://input");

// Decoding the json data to retrieve based on objects
$request = json_decode($post_data, true);

$debug = 0;
if($debug) {
  // collecting all the json data
  $reference_num = '5cf0b3e92ba26';
  $email = 'savy.1712@gmail.com';
  $is_less_18 = '0';
  $parent_first_name = 'asa';
  $parent_last_name = 'ascd';
  $signature = 'ascds';
  $date_consent = '2020-01-01 10:30:14';
} else {
  // collecting all the json data
  $reference_num = $request['ref_num'];
  $email = $request['email'];
  $is_less_18 = $request['is_less_than_18'];
  $parent_first_name = $request['parent_first_name'];
  $parent_last_name = $request['parent_last_name'];
  $signature = $request['signature'];
  $date_consent = $request['date_consent'];
}

if(!$reference_num || !$email) {
  echo json_encode('ref num/email not present');
  return;
}

function create_signature_file($signature, $signature_path) {
 // make signature file
 if (is_dir(get_data_path().'/'.$reference_num)) {
   $signature_file_open = fopen($signature_path, 'w+');
   if (fwrite($signature_file_open, $signature) == 0) {
     // file write failure in the signature file
     return -2;
   }
   fclose($signature_file_open);
   return 1;
 } else {
     return -1;
 }
}

function insert_values($con, $data, $params, $table_name, $types, $primary_key, $foreign_key) {
  $insert_prepare = $con->prepare ('INSERT INTO'.' '. $table_name.' (date_consent, is_less_than_18, parent_first_name, parent_last_name, email, reference_num, signature_path) VALUES ( ?, ?, ?, ?, ?, ?, ?)');
  $insert_prepare->bind_param("sssssss", $date_consent_t, $is_less_than_18_t, $parent_first_name_t, $parent_last_name_t, $email_t, $reference_num_t, $signature_path);
  $date_consent_t = $data[0];
  $is_less_than_18_t = $data[1];
  $parent_first_name_t = $data[2];
  $parent_last_name_t = $data[3];
  $email_t = $data[4];
  $reference_num_t = $data[5];
  $signature_path = $data[6];
  if ($insert_prepare != '' && $insert_prepare->execute()) {
    $insert_prepare->close();
    return 1;
  } else {
      if ($insert_prepare != '') {
        $insert_prepare->close();
      }
      return -1;
  }
}


function update_values($con, $table_name, $date, $page_num, $reference_num) {
  $update_prepare = $con->prepare('UPDATE  '. $table_name.'  SET submitted_date=?, page_completed=? WHERE reference_num=?');
  // preparing for the update command
  if ($update_prepare != NULL) {
    // updating the page number and submitted date
    $update_prepare->bind_param("sss", $date_t, $page_num_t, $reference_num_t);
    $date_t = $date;
    $page_num_t = $page_num;
    $reference_num_t = $reference_num;
    if ($update_prepare != '' && $update_prepare->execute()) {
      $update_prepare->close();
      return 1;
    } else {
        if ($update_prepare != '') {
          $update_prepare->close();
        }
        return -1;
    }
  } else {
      return -2;
  }
}



// establish the connection with the DB
$con = get_connection_db($login_information, $database_name);

if ($con != NULL) {
   $data_signature_path = './../data/'.$reference_num.'/signature_pad.txt';
   $signature_path = get_data_path().'/'.$reference_num.'/signature_pad.txt';

   $data = array($date_consent, $is_less_18, $parent_first_name, $parent_last_name, $email, $reference_num, $data_signature_path);
   $params = array ('date_consent', 'is_less_than_18', 'parent_first_name', 'parent_last_name', 'email', 'reference_num', 'signature_path');
   $types = array( 'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')',
                   'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')', 'VARCHAR('.$var_char_len.')');
   $primary_key = 'PRIMARY KEY(reference_num, email)';
   $foreign_key = 'FOREIGN KEY(reference_num, email) REFERENCES '.get_main_customer_table_name(). '(reference_num, email)';
   $table_name = consent_table();

   $create_signature = create_signature_file($signature, $signature_path);

   // signature creation
   if($create_signature === 1) {
     /* inserting into the consent table  */
     $insert_prepare = insert_values($con, $data, $params, $table_name, $types, $primary_key, $foreign_key);
     /* Executing the mysql execution statement */
     if ($insert_prepare == 1) {
         /* Updating the customer_main table with page number and submission date */
         $update_cmd = update_values($con, get_main_customer_table_name(), $date_consent, 'end', $reference_num);
         if($update_cmd === 1) {
           echo json_encode('success');
         } else {
             echo json_encode('ns');
         }
     } else {
         echo json_encode('NS');
     }
   } else if ($create_signature === -1) {
       echo json_encode('reference number folder not present');
   } else {
       echo json_encode('signature file not written && insert consent table not done');
   }
   $con->close();
} else {
    echo json_encode('connection failure');
}





?>
