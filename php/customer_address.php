<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

include_once 'login.php';
include_once 'names.php';
include_once 'customer_address_create_insert.php';

$debug = debug_check();

if($debug) {
  echo json_encode('Inside customer_address.php');
}

function get_main_addr_details($ref_num, $email, $type, $street_address, $suburb, $state, $postcode, $current_year,
                               $current_month, $five_years_only_current_address, $five_years_current_previous_address, $postal_same_address, $var_len) {
  $create_data = array( $ref_num, $email,$type, $street_address, $suburb, $state, $postcode, $current_year,
                       $current_month, $five_years_only_current_address, $five_years_current_previous_address, $postal_same_address);
  $create_types = array('VARCHAR('.$var_len.')', 'VARCHAR('.$var_len.')', 'VARCHAR('.$var_len.') NOT NULL' , 'VARCHAR('.$var_len.') NOT NULL',
                        'VARCHAR('.$var_len.') NOT NULL', 'VARCHAR('.$var_len.') NOT NULL', 'VARCHAR('.$var_len.') NOT NULL', 'INT NOT NULL', 'INT NOT NULL', 'VARCHAR('.$var_len.')',
                        'VARCHAR('.$var_len.')', 'VARCHAR('.$var_len.')');
  $create_params = array ('reference_num', 'email', 'type_address', 'street_address', 'suburb', 'state', 'postcode', 'year_stay', 'month_stay',
                          'five_years_only_current_address', 'five_years_current_previous_address', 'postal_same_address');
  $create_primary_key  = ' PRIMARY KEY (reference_num, email) ';
  $create_foreign_key  = ' FOREIGN KEY(reference_num, email) REFERENCES '.get_main_customer_table_name().'(reference_num, email) ON UPDATE CASCADE ';

  return array($create_data, $create_types, $create_params, $create_primary_key, $create_foreign_key);
}

function create_insert_main($con, $comma, $ref_num, $email, $type, $street_address, $suburb, $state, $postcode, $current_year,
                            $current_month, $five_years_only_current_address, $five_years_current_previous_address, $postal_same_address,  $var_len) {
  /* Run the create operation for the address checks */
  $formed_result = get_main_addr_details($ref_num, $email, $type, $street_address, $suburb, $state, $postcode, $current_year,
                                         $current_month, $five_years_only_current_address, $five_years_current_previous_address, $postal_same_address,  $var_len);
  $create_data = $formed_result[0];
  $create_types = $formed_result[1];
  $create_params = $formed_result[2];
  $create_primary_key = $formed_result[3];
  $create_foreign_key = $formed_result[4];

  $create = create_operations($con, get_customer_main_address(), $create_data, $create_params, $create_types, $comma, $create_primary_key, $create_foreign_key);
  if ($create == 'success') {
    /* Once the table is successfully created */
    $insert_command = insert_query_address($con, get_customer_main_address(), $create_data, $create_params, 0);
    if($insert_command == 'success') {
      return 1;
    } else {
        if($debug) {
          echo json_encode('ni-main-addr');
        }
        return -1;
    }
  } else {
      if($debug) {
        echo json_encode($create);
      }
      return -2;
  }
}


function get_postal_addr_details($ref_num, $email, $type, $postal_street_address, $postal_suburb, $postal_state, $postal_postcode, $var_len) {
  $create_data = array( $ref_num, $email, $type, $postal_street_address, $postal_suburb, $postal_state, $postal_postcode);
  $create_types = array('VARCHAR('.$var_len.')', 'VARCHAR('.$var_len.')', 'VARCHAR('.$var_len.') NOT NULL' , 'VARCHAR('.$var_len.') NOT NULL',
                        'VARCHAR('.$var_len.') NOT NULL', 'VARCHAR('.$var_len.') NOT NULL', 'VARCHAR('.$var_len.') NOT NULL' );
  $create_params = array ('reference_num', 'email', 'type_address', 'street_address', 'suburb', 'state', 'postcode');
  $create_primary_key  = ' PRIMARY KEY (reference_num, email) ';
  $create_foreign_key  = ' FOREIGN KEY(reference_num, email) REFERENCES '.get_customer_main_address().'(reference_num, email) ON UPDATE CASCADE ';
  return array($create_data, $create_types, $create_params, $create_primary_key, $create_foreign_key);

}


function create_insert_postal($con, $comma, $ref_num, $email, $type, $postal_street_address, $postal_suburb, $postal_state, $postal_postcode, $var_len) {
  $formed_result = get_postal_addr_details($ref_num, $email, $type, $postal_street_address, $postal_suburb, $postal_state, $postal_postcode, $var_len);
  $create_data = $formed_result[0];
  $create_types = $formed_result[1];
  $create_params = $formed_result[2];
  $create_primary_key = $formed_result[3];
  $create_foreign_key = $formed_result[4];

  $postal_create = create_operations($con, get_customer_postal_address(), $create_data, $create_params, $create_types, $comma, $create_primary_key, $create_foreign_key);
  if ($postal_create == 'success') {
    /* Once the table is successfully created */
    $postal_insert_command = insert_query_address($con, get_customer_postal_address(), $create_data, $create_params, 0);
    if($postal_insert_command == 'success') {
      return 1;
    } else {
        if($debug) {
          echo json_encode('ni-main-addr');
        }
        return -1;
    }
  } else {
      if($debug) {
        echo json_encode($postal_create);
      }
      return -2;
  }
}


function get_previous_addr_details_t($ref_num, $email, $type, $previous_address, $var_len) {
  $create_data = array();

  // Previous address list of information
  for($i=0; $i < count($previous_address); $i++) {

    $create_data[$i] = array($ref_num, $email, $type, $i, $previous_address[$i]['street'], $previous_address[$i]['suburb'], $previous_address[$i]['state'],
                           $previous_address[$i]['postcode'], $previous_address[$i]['country'],  $previous_address[$i]['year'], $previous_address[$i]['month'] );
    // echo json_encode($create_data[$i]);
  }

  $create_types = array('VARCHAR('.$var_len.')', 'VARCHAR('.$var_len.')', 'VARCHAR('.$var_len.') NOT NULL' , 'VARCHAR('.$var_len.') NOT NULL' , 'VARCHAR('.$var_len.') NOT NULL',
                        'VARCHAR('.$var_len.') NOT NULL', 'VARCHAR('.$var_len.') NOT NULL', 'VARCHAR('.$var_len.') NOT NULL',  'VARCHAR('.$var_len.') NOT NULL',
                        'INT NOT NULL', 'INT NOT NULL');
  $create_params = array ('reference_num', 'email', 'type_address', 'PID', 'street_address', 'suburb', 'state', 'postcode', 'country', 'year_stay', 'month_stay');
  $create_primary_key  = ' PRIMARY KEY(PID) ';
  $create_foreign_key  = ' FOREIGN KEY(reference_num, email) REFERENCES '.get_customer_main_address().'(reference_num, email) ON UPDATE CASCADE ';

  return array($create_data, $create_types, $create_params, $create_primary_key, $create_foreign_key);
}


function create_insert_previous_t($con, $comma, $ref_num, $email, $type, $previous_address, $var_len) {

  $formed_result = get_previous_addr_details_t($ref_num, $email, $type, $previous_address, $var_len);
  $create_data = $formed_result[0];
  $create_types = $formed_result[1];
  $create_params = $formed_result[2];
  $create_primary_key = $formed_result[3];
  $create_foreign_key = $formed_result[4];

  // previous create operation with first data passing as per standards of column dimension
  $previous_create = create_operations($con, get_customer_previous_address(), $create_data[0], $create_params, $create_types, $comma, $create_primary_key, $create_foreign_key);
  // echo json_encode($previous_create);
  if ($previous_create == 'success') {
    // Once the table is successfully created
    for($i = 0; $i < count($create_data); $i++) {
      // echo json_encode($create_data[$i]);
      $previous_insert_command = insert_query_address($con, get_customer_previous_address(), $create_data[$i], $create_params, $i);
      // echo json_encode($previous_insert_command);
      if($previous_insert_command != 'success') {
        if( $debug) {
          echo json_encode('ni-main-addr');
        }
        return -1;
      }
    }
    return 1;
  } else {
      if($debug) {
        echo json_encode($previous_create);
      }
      return -2;
  }
}

function update_page_number($con, $page_number, $ref_num) {
  $update_create = update_information($con, $page_number, get_main_customer_table_name(), $ref_num);
  if($update_create == 'success') {
    return 1;
  } else {
      return 0;
  }
}

/* Getting the database name */
$database_name = get_db_name();
/* Getting the login file information */
$login_information =  '/'.get_login_file();

/* Retrieve all the contents from the info page for updating the values */
$post_data = file_get_contents("php://input");

/* Decoding the json data to retrieve based on objects */
$request = json_decode($post_data, true);

/* Getting all the information passed on from the front end application */

if($debug) {
  $ref_num = '5c53db44a89b8';
  $email =  'savy.1712@gmail.com';
  $street_address = '119 Brigalow Street';
  $suburb = 'Lyneham';
  $state = 'ACT';
  $postcode = '2602';
  $current_year = 3;
  $current_month = 1;
  $postal_street_address = $street_address;
  $postal_suburb = $suburb;
  $postal_state = $state;
  $postal_postcode = $postcode;
  $previous_address = array(array("street"=>"119 Brigalow Street", "suburb"=>"Lyneham","state"=> "ACT","postcode"=> "2602", "country"=>'Australia', "year"=>5, "month"=>1), array("street"=>"119 Brigalow Street", "suburb"=>"Lyneham","state"=> "ACT","postcode"=> "2602", "country"=>"Australia", "year"=>5, "month"=>1) );
  $five_years_only_current_address = 'yes'; //: this.five_years_current_address,
  $five_years_current_previous_address = 'yes' ;//: this.five_years_previous_address,
  $page_number = 5; // 'page_number': '5'
  $postal_same_address = 'no';
  $from_info3 = 0;
} else {
  $ref_num = $request['ref_num']; // : this.ref_num,
  $email =  $request['email']; // : this.email,
  $street_address = $request['street_address']; // : this.street_address,
  $suburb = $request['suburb'];//: this.suburb,
  $state = $request['state']; // : this.state,
  $postcode = $request['postcode']; //: this.postcode,
  $current_year = $request['year'] ; //: this.lived_year,
  $current_month = $request['month']; // : this.lived_month,
  $postal_street_address = $request['postal_street_address'];// : this.postal_street_address,
  $postal_suburb = $request['postal_suburb'];//: this.postal_suburb,
  $postal_state = $request['postal_state']; //: this.postal_state,
  $postal_postcode = $request['postal_postcode']; //: this.postal_postcode,
  $previous_address = $request['previous_address'];//: this.pastAddress,
  $five_years_only_current_address = ($request['five_years_only_current_address'] ) ? 'yes' : 'no';//: this.five_years_current_address,
  $five_years_current_previous_address = ($request['five_years_current_previous_address']) ? 'yes' : 'no'; //: this.five_years_previous_address,
  $page_number = $request['page_number']; // 'page_number': '5'
  $postal_same_address = ($request['postal_same_address'] ) ? 'yes' : 'no';
  $from_info3 = $request['from_info3'];
}

/**/
$con = NULL;
$database_name  = get_db_name();
$create_params = '';
$create_types = '';
$create_data = '';
$create_primary_key = '';
$create_foreign_key = '';
$comma = ',';
$var_len = '255';
$main_flag = 0;
$postal_flag = 0;
$previous_flag = 0;
$update_flag = 0;

/* Establish the connection with the database */
if(($con = get_connection_db($login_information, $database_name)) != NULL ) {

  if(!$from_info3) {
    $update_flag = update_page_number($con, $page_number, $ref_num);
  }
  /* main flag to find out if the main address table is created or not */
  $main_flag = create_insert_main($con, $comma, $ref_num, $email, 'CURRENT', $street_address, $suburb, $state, $postcode, $current_year,
                                  $current_month, $five_years_only_current_address, $five_years_current_previous_address, $postal_same_address, $var_len);

  /* Postal address different from main address table given */
  if (!($postal_same_address == 'yes')) {
    $postal_flag = create_insert_postal($con, $comma, $ref_num, $email, 'POSTAL', $postal_street_address, $postal_suburb, $postal_state, $postal_postcode, $var_len);
  }

  if(count($previous_address) > 0) {
    $previous_flag = create_insert_previous_t($con, $comma, $ref_num, $email, 'PREVIOUS', $previous_address, $var_len);
    // echo json_encode(count($previous_address));
  }

  // flagging for storing in all three database at the same time
  if($main_flag &&  ($from_info3  || $update_flag) ) {
    if( ($previous_flag || count($previous_address) === 0 ) && ($postal_same_address == 'yes' || $postal_flag) )  {
      echo json_encode('success');
    } else {
        echo json_encode('ns');
    }
  } else {
      echo json_encode('ns');
  }

  /* Closing the established database connection */
  if($con != NULL) {
    $con->close();
  }
}

?>
