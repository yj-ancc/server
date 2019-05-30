<?php

include 'normalised_create_insert.php';


function delete_record($con, $table_name, $ref_num, $email) {

  $delete_query = '';
  $delete_command = '';

  if ($con != NULL) {
    if ($table_name != NULL) {
      /* delete query to execute based on the table name */
      $delete_query = "DELETE FROM ".$table_name."  WHERE reference_num=? AND email=? ";
      /* delete command to execute the delete query */
      $delete_command = $con->prepare($delete_query);
      if($delete_command != NULL) {
        $delete_command->bind_param('ss', $ref_t, $email_t);
        $ref_t = $ref_num;
        $email_t = $email;
      } else {
          if($debug) {
            echo json_encode('delete command not formed');
          }
          return -2;
      }
      /* Executing the delete command operation */
      $delete_exec = $delete_command->execute();
      if($delete_exec) {
        return 1;
      } else if( $debug_exec != NULL ) {
          if($debug) {
            echo json_encode('delete exec failed');
          }
          $delete_exec->close();
          return -3;
      }
    } else {
        if($debug) {
          echo json_encode('table name mentioning error');
        }
        return -1;
    }
  } else {
      if($debug) {
        echo json_encode('connection failure');
      }
      return 0;
  }
}

function get_previous_addr_details($ref_num, $email, $type, $previous_address, $var_len) {
  $create_data = array();
  // Previous address list of information
  for($i=0; $i < count($previous_address); $i++) {
    $create_data[$i] = array($ref_num, $email, $type, ''+$i, $previous_address[$i]['street'], $previous_address[$i]['suburb'], $previous_address[$i]['state'],
                           $previous_address[$i]['postcode'],$previous_address[$i]['country'],  $previous_address[$i]['year'], $previous_address[$i]['month'] );
  }
  $create_params = array ('reference_num', 'email', 'type_address', 'PID', 'street_address', 'suburb', 'state', 'postcode', 'country', 'year_stay', 'month_stay');
  return array($create_data, $create_params);
}

function create_insert_previous($con, $comma, $ref_num, $email, $type, $previous_address, $var_len) {
  $formed_result = get_previous_addr_details($ref_num, $email, $type, $previous_address, $var_len);
  $create_data = $formed_result[0];
  $create_params = $formed_result[1];

  // Once the table is successfully created
  for($i = 0; $i < count($create_data); $i++) {
    $previous_insert_command = insert_query_address($con, get_customer_previous_address(), $create_data[$i], $create_params, $i);
    if($previous_insert_command != 'success') {
      if($debug) {
        echo json_encode('ni-main-addr');
      }
      return -1;
    }
  }
    return 1;
}


function create_operations($con, $table_name, $data, $params, $types, $comma, $primary_key, $foreign_key) {
  $combined_param = '';
  $create_query = '';

  /* Forming the check attributes */
  $combined_param = check_attributes($data, $params, $types, $comma, $primary_key, $foreign_key, 0);

  if($combined_param == '' ) {
    return 'nc-attr-cus';
  }

  /* Check attributes are formed and triggering to form the table  */
  $create_query = 'CREATE TABLE IF NOT EXISTS '.$table_name. ' ( '.$combined_param.' ) ';
  if($con->query($create_query) != NULL ) {
    return 'success';
  } else {
      return 'nc-table-addr';
  }
}

function insert_prepare_query($table_name, $data, $params) {

  $insert_prepare_string = '';
  $comma = '';

  /* Forming the first half of the insert statement and preparing it for the binding */
  $insert_prepare_string = 'INSERT INTO'.' '.$table_name.' (';
  for ($i = 0; $i < count($params); $i++) {
    if($i == count($params) - 1) {
      $comma = '';
    } else {
        $comma = ',';
    }
    $insert_prepare_string .= $params[$i]. $comma;
  }
  $insert_prepare_string .= ') VALUES ( ';
  /* Forming the second half of the insert statement and preparing it for the binding */
  $comma = '';
  for ($i = 0; $i < count($params); $i++) {
    if($i < count($params) - 1) {
      $comma = ',';
    } else {
        $comma = '';
    }
    $insert_prepare_string .= '?'.$comma;
  }
  $insert_prepare_string .= ')';

  /* Returning the insert prepare string */
  return $insert_prepare_string;
}


function update_information($con, $page_number, $table_name, $ref_num) {
  $number = '';
  $ref = '';
  $update_page_number = '';

  $update_page_number = 'UPDATE '.$table_name.' SET page_completed=? WHERE reference_num=?';
  if($update_page_number) {
    $result_page_number = $con->prepare($update_page_number);
    if($result_page_number != NULL) {
      $result_page_number->bind_param('ss', $number, $ref);
      $number = $page_number;
      $ref = $ref_num;
    }
    /* Setting the page number based on the info page  */
    $exec = $result_page_number->execute();
    if($exec) {
      $result_page_number->close();
      return 'success';
    } else if($exec != '') {
        $result_page_number->close();
        return 'fail:info-no-exec';
    } else {
        return 'fail:info-no-res';
    }
  }
}

function insert_query_address($con, $table_name, $data, $params, $i) {
  $ref_num = '';
  $email = '';
  $street_address = '';
  $suburb = '';
  $state = '';
  $postcode = '';
  $current_year = '';
  $current_month = '';
  $postal_street_address = '';
  $postal_suburb = '';
  $postal_state = '';
  $postal_postcode = '';
  $previous_street_address = '';
  $previous_suburb = '';
  $previous_state = '';
  $previous_postcode = '';
  $previous_year = '';
  $previous_month = '';
  $five_years_only_current_address = '';
  $five_years_current_previous_address = '';
  $page_number = '';
  $postal_same_address = '';
  $type_address = '';
  $pid = '';

  $name_type = '';
  $previous_first_name = '';
  $previous_last_name = '';
  $previous_middle_name = '';
  $previous_is_single_name = '';

  /* Insert prepare statement */
  $insert_prepare = '';
  $insert_prepare_string = insert_prepare_query($table_name, $data, $params);

  /* At a time, only one table insertion of values will be happening */
  if( $table_name == get_customer_main_address()) {
    /* Inserted string values are NOT NULL */
    if($insert_prepare_string != '') {
      $insert_prepare = $con->prepare($insert_prepare_string);
      /* Prepared statement of the MySQL is executing */
      if($insert_prepare != '') {
        $insert_prepare->bind_param("sssssssiisss", $ref_num, $email, $type_address, $street_address, $suburb, $state, $postcode, $current_year, $current_month, $five_years_only_current_address, $five_years_current_previous_address, $postal_same_address);
        $ref_num = $data[0];
        $email = $data[1];
        $type_address = $data[2];
        $street_address = $data[3];
        $suburb = $data[4];
        $state = $data[5];
        $postcode = $data[6];
        $current_year = $data[7];
        $current_month = $data[8];
        $five_years_only_current_address = $data[9];
        $five_years_current_previous_address = $data[10];
        $postal_same_address = $data[11];
      } else {
          return 'ni-prep-bind-main-addr';
      }
    } else {
        return 'ni-prep-str-main-addr';
    }
  } else if ($table_name == get_customer_postal_address()) {
      if($insert_prepare_string != '') {
        $insert_prepare = $con->prepare($insert_prepare_string);
        /* Prepared statement of the MySQL is executing */
        if($insert_prepare != '') {
          $insert_prepare->bind_param("sssssss",$ref_num, $email, $type_address, $postal_street_address, $postal_suburb, $postal_state, $postal_postcode);
          $ref_num = $data[0];
          $email = $data[1];
          $type_address = $data[2];
          $postal_street_address = $data[3];
          $postal_suburb = $data[4];
          $postal_state = $data[5];
          $postal_postcode = $data[6];
        } else {
            return 'ni-postal-prep-addr';
        }
      } else {
          return 'ni-postal-str-addr';
      }
  } else if ($table_name == get_customer_previous_address()) {
      if($insert_prepare_string != '') {
        /* Preparing the insert string for processing the previous address list */
        $insert_prepare = $con->prepare($insert_prepare_string);
        /* Prepared statement of the MySQL is executing */
        if($insert_prepare != '') {
          /*  $create_params = array ('reference_num', 'email', 'type_address', 'street_address', 'suburb', 'state', 'postcode', 'year_stay', 'month_stay'); */
          $insert_prepare->bind_param("sssssssssii",$ref_num, $email, $type_address, $pid, $previous_street_address, $previous_suburb, $previous_state,
                                                  $previous_postcode, $previous_country, $previous_year, $previous_month);
          $ref_num = $data[0];
          $email = $data[1];
          $type_address = $data[2];
          $pid = $ref_num .'_'. $data[3];
          $previous_street_address = $data[4];
          $previous_suburb = $data[5];
          $previous_state = $data[6];
          $previous_postcode = $data[7];
          $previous_country = $data[8];
          $previous_year = $data[9];
          $previous_month = $data[10];
        } else {
            return 'ni-prev-prep-addr';
        }
      } else {
          return 'ni-prev-str-addr';
      }
  } else if ($table_name == get_customer_previous_name()) {
      if($insert_prepare_string != '') {
        /* Preparing the insert string for processing the previous address list */
        $insert_prepare = $con->prepare($insert_prepare_string);
        /* Prepared statement of the MySQL is executing */
        if($insert_prepare != '') {
           $insert_prepare->bind_param("ssssssss",$ref_num, $email, $pid, $name_type, $previous_first_name, $previous_middle_name, $previous_last_name,
                                                   $previous_is_single_name);
           $ref_num = $data[0];
           $email = $data[1];
           $pid = $ref_num .'_'. $data[2];
           $name_type = $data[3];
           $previous_first_name = $data[4];
           $previous_middle_name = $data[5];
           $previous_last_name = $data[6];
           $previous_is_single_name = $data[7];
         } else {
            return 'ni-prep-prev';
         }
       } else {
          return 'ni-prev-str';
      }
  }

  /* preparing the execute statement for the customer address table */
  if ($insert_prepare != '' && $insert_prepare->execute()) {
    $insert_prepare->close();
    return 'success';
  } else  if ($insert_prepare != '') {
      $insert_prepare->close();
      return 'ns-addr';
  } else {
      return 'ns';
  }
}

?>
