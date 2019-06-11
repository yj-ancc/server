<?php
header('Content-Type', 'application/json');


function get_page_number($con, $ref_num, $email, $table_name) {
  $select_cmd = 'SELECT page_completed FROM '.$table_name.' WHERE reference_num="'.$ref_num.'" AND email="'.$email.'"';
  $select_page_number = $con->query($select_cmd);
  if ($select_page_number != NULL) {
    // bind the parameters
    $row = mysqli_fetch_assoc($select_page_number);
    if ($row != NULL) {
      // row fetching issue
      if ($row['page_completed'] != NULL) {
        return $row['page_completed'];
      } else {
          return -4;
      }
      // echo json_encode('success');
      $select_page_number->close();
    } else {
        // no value
        return -2;
    }
  } else {
      // select command not prepared
      return -1;
  }
}

function get_customer_information($con, $ref_num, $email, $table_name) {
  $select_cmd = 'SELECT first_name, middle_name, last_name, page_completed, phone_num, started_date, from_location,';
  $select_cmd .= 'is_single_name FROM '.$table_name.' WHERE reference_num="'.$ref_num.'" AND email="'.$email.'"';
  $select_stmt = $con->query($select_cmd);
  if ($select_stmt != NULL) {
    // bind the parameters
    $row = mysqli_fetch_assoc($select_stmt);
    if ($row != NULL) {
      // row fetching issue
      if ( $row['first_name']
           && array_key_exists("middle_name", $row)
           && array_key_exists("last_name", $row)
           && array_key_exists("is_single_name", $row)
           && array_key_exists("phone_num", $row)
           && array_key_exists("started_date", $row)
           && array_key_exists("from_location", $row)
           && $row['page_completed']) {
        $json = array( "first_name" => $row['first_name'],
        "middle_name" => $row['middle_name'],
        "last_name"=> $row['last_name'],
        "phone_num"=> $row['phone_num'],
        "page_completed" => $row['page_completed'],
        "started_date" => $row['started_date'],
        "from_location" => $row['from_location'],
        "is_single_name" =>  $row['is_single_name']);
        // echo json_encode($json);
        return $json;
      } else {
          return array("error" => '-4');
      }
      $select_stmt->close();
    } else {
        // no value
        return array("error" => '-2');
    }
  } else {
      // select command not prepared
      return array("error" => '-1');
  }
}


function get_payment_sec_info($con, $id, $table_name, $customer_info, $main) {
  $curr_pay_card = 'payment_card';
  $curr_pay_paypal = 'payment_paypal';
  $select_cmd = '';
  // table name checks happening
  if($table_name == $curr_pay_card) {
    $select_cmd = 'SELECT payment_status, last_4, card_type, processing_fee FROM '.$table_name.' WHERE payment_id="'.$id.'"';
  } else if ($table_name == $curr_pay_paypal) {
       $select_cmd = 'SELECT payment_status FROM '.$table_name.' WHERE payment_id="'.$id.'"';
  } else {
      return array('error' => 'table name not supported in this version');
  }
  // select statement working
  $select_stmt = $con->query($select_cmd);
  if ($select_stmt != NULL) {
    // bind the parameters
    $row = mysqli_fetch_assoc($select_stmt);
    if ($row != NULL) {
      // row fetching issue
      if($table_name == $curr_pay_card) {
        if ($row['payment_status'] != NULL && $row['last_4'] != NULL && $row['card_type'] != NULL && $row['processing_fee'] != NULL) {
          return array('payment_status' => $row['payment_status'],
                       'last_4' => $row['last_4'],
                       'card_type' => $row['card_type'],
                       'processing_fee' => $row['processing_fee'],
                       'error'=> 'no',
                       'page' => '3',
                       'first_name' => $customer_info['first_name'],
                       'middle_name' => $customer_info['middle_name'],
                       'last_name' => $customer_info['last_name'],
                       'phone_num' => $customer_info['phone_num'],
                       'started_date' => $customer_info['started_date'],
                       'from_location' => $customer_info['from_location'],
                       'is_single_name' => $customer_info['is_single_name'],
                       'page_completed' => $customer_info['page_completed'],
                       'payment_type' => $main['payment_type'],
                       'type_of_check' => $main['type_of_check'],
                       'payment_id' => $main['payment_id'],
                       'total_paid' => $main['total_paid'],
                       );
        } else {
            return array('error'=> 'row not fetched');
        }
      } else if ($table_name == $curr_pay_paypal) {
          if($row['payment_status'] != NULL) {
            return array("payment_status"=>$row['payment_status'],
                         'error'=>'no',
                         'page' => '3',
                         'first_name' => $customer_info['first_name'],
                         'middle_name' => $customer_info['middle_name'],
                         'last_name' => $customer_info['last_name'],
                         'phone_num' => $customer_info['phone_num'],
                         'started_date' => $customer_info['started_date'],
                         'from_location' => $customer_info['from_location'],
                         'is_single_name' => $customer_info['is_single_name'],
                         'page_completed' => $customer_info['page_completed'],
                         'payment_type' => $main['payment_type'],
                         'type_of_check' => $main['type_of_check'],
                         'payment_id' => $main['payment_id'],
                         'total_paid' => $main['total_paid'],
                         );
          } else  {
              return array('error' => 'row not fetched');
          }
      } else {
          return array("error"=> 'table name not supported in this version');
      }
      $select_stmt->close();
    } else {
        // no value
        return array('error'=>'no matching value');
    }
  } else {
      // select command not prepared
      return array('error' => 'failed to process');
  }
}

function get_payment_table_det($con, $ref_num, $email, $table_name) {
  $select_cmd = 'SELECT payment_type, type_of_check, payment_id, total_paid FROM '.$table_name.' WHERE reference_num="'.$ref_num.'" AND email="'.$email.'"';
  $select_stmt = $con->query($select_cmd);
  if ($select_stmt != NULL) {
    // bind the parameters
    $row = mysqli_fetch_assoc($select_stmt);
    if ($row != NULL) {
      // row fetching issue
      if ($row['payment_type'] != NULL && $row['type_of_check'] != NULL && $row['payment_id'] != NULL && $row['total_paid'] != NULL) {
        return array('payment_type' => $row['payment_type'],
                     'type_of_check' => $row['type_of_check'],
                     'payment_id' => $row['payment_id'],
                     'total_paid' => $row['total_paid'],
                     'error'=> 'no', 'page' => '3',
                     'first_name' => $customer_info['first_name'],
                     'middle_name' => $customer_info['middle_name'],
                     'last_name' => $customer_info['last_name'],
                     'phone_num' => $customer_info['phone_num'],
                     'started_date' => $customer_info['started_date'],
                     'from_location' => $customer_info['from_location'],
                     'is_single_name' => $customer_info['is_single_name'],
                     'page_completed' => $customer_info['page_completed']);
      } else {
          return array('error'=> 'row not fetched');
      }
      $select_stmt->close();
    } else {
        // no value
        return array('error'=>'no matching value');
    }
  } else {
      // select command not prepared
      return array('error' => 'failed to process');
  }

}


?>
