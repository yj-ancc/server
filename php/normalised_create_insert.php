<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


function check_attributes( $data, $params, $types, $comma, $primary_key, $foreign_key, $only_create) {
    /* Checking if the data and params are matching */
    $combined_param = '';
    if(count($data) == count($params)) {
      if((count($params) == count($data)) || $only_create) {
        for($i = 0; $i < count($params); $i++) {
          /* Forming the resultant string */
          if ($i == count($params) - 1) {
            if( $foreign_key == '' && $primary_key == '') {
              $comma = '';
            }
          }
          $combined_param .= $params[$i].' '.$types[$i].$comma;
        }
        if($foreign_key == '') { $comma = ''; }
        if($primary_key == '') { $comma = ''; }
        $combined_param .= $primary_key. $comma. $foreign_key;
        return $combined_param;
      } else {
          return '';
      }
    } else {
        return '';
    }
}

function create_insert_table($con, $data, $params, $table_name, $types, $primary_key, $foreign_key, $invoice) {
    $combined_param = '';
    $insert_values = '';
    $comma = ',';

    $combined_param = check_attributes($data, $params, $types, $comma, $primary_key, $foreign_key, 0);
    if($combined_param == '') return 'nc-attr-cus';

    /* Invoice number is a part of the customer main table : reason for considering only the main customer table */
    if ( $table_name == get_main_customer_table_name() ) {
      /* Generating the invoice number based on run time method */
      $generate_invoice = 'select * from  `'.get_main_customer_table_name().'` LIMIT 1';
      if($con->query($generate_invoice) != TRUE ) {
        /* table doesn't exist and it is yet to be created */
        $invoice = $data[count($data) - 1];
      } else {
          /* table does exists in the database */
          $max_invoice = 'select max(invoice_num) as max from '.get_main_customer_table_name();
          $result = $con->query($max_invoice);
          if( $result ) {
            $row = mysqli_fetch_assoc($result);
            $invoice = (int) $row['max'] + 1;
        }
      }
    }

    /* create table query */
    $create_query = "CREATE TABLE IF NOT EXISTS  ".$table_name. ' ('.$combined_param.' )';

    if($con->query($create_query) == TRUE ) {
      /* create query for creating the database and insert the values into the tables created */
      if ($table_name == get_main_customer_table_name()) {
        $insert_prepare = $con->prepare ('INSERT INTO'.' '. get_main_customer_table_name().' (reference_num, email, phone_num, type_of_check, page_completed, started_date, submitted_date, invoice_num) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)');
        $insert_prepare->bind_param("sssssssi", $ref_num, $email, $phone, $type, $page_completed, $date, $submitted_date, $invoice_val );
        // $data = array($ref_num, $email, $phone_num, $type_check, $page_completed, $started_date);
        $ref_num = $data[0];
        $email = $data[1];
        $phone = $data[2];
        $type = $data[3];
        $page_completed = $data[4];
        $date = $data[5];
        $submitted_date = $data[6];
        $invoice_val = $invoice;
      } else if ($table_name == get_sec_customer_table_name()) {
          $insert_prepare = $con->prepare ('INSERT INTO '.$table_name.' (reference_num, first_name, middle_name, last_name, email, is_single_name, last_logged, from_location, dob, gender, state_born, country_born, is_prev_name) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
          $insert_prepare->bind_param("sssssssssssss", $ref_num, $first_name, $middle_name, $last_name, $email, $is_single_name, $last_logged, $from_location, $dob, $gender, $state, $country, $prev_name );
          //  $data = array($ref_num, $first_name, $middle_name, $last_name, $email, $is_single_name, $last_logged, $from_location, '', '', '', '');
          $ref_num = $data[0];
          $first_name =   $data[1];
          $middle_name =  $data[2];
          $last_name = $data[3];
          $email =  $data[4];
          $is_single_name = $data[5];
          $last_logged = $data[6];
          $from_location = $data[7];
          $dob = $data[8];
          $gender = $data[9];
          $state = $data[10];
          $country = $data[11];
          $prev_name = $data[12];
      }
    } else {
      echo json_encode('creation failure inside the normalised create insert.php');
      return -1;
    }

    /* Executing the mysql execution statement */
    if ($insert_prepare != '' && $insert_prepare->execute()) {
        $insert_prepare->close();
        return $invoice;
    } else {
        if ($insert_prepare != '') {
          $insert_prepare->close();
        }
        return -2;
    }
}


?>
