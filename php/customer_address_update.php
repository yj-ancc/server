<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


include_once 'names.php';
include_once 'login.php';
include_once 'customer_address_create_insert.php';

$debug = debug_check();

/* Getting the database name */
$database_name = get_db_name();
/* Getting the login file information */
$login_information =  '/'.get_login_file();

/* Retrieve all the contents from the info page for updating the values */
$post_data = file_get_contents("php://input");

/* Decoding the json data to retrieve based on objects */
$request = json_decode($post_data, true);

if($debug) {
  $ref_num = '5c53db44a89b8';
  $email =  'savy.1712@gmail.com';
  $street_address = '119 Brigalow StreetW';
  $suburb = 'Lyneham';
  $state = 'ACT';
  $postcode = '2602';
  $current_year = 3;
  $current_month = 1;
  $postal_street_address = $street_address;
  $postal_suburb = $suburb;
  $postal_state = $state;
  $postal_postcode = $postcode;
  $previous_address = array(array("street"=>"119 Brigalow Street", "suburb"=>"Lyneham","state"=> "ACT","postcode"=> "2603", "country"=> "Australia", "year"=>5, "month"=>1), array("street"=>"119 Brigalow Street", "suburb"=>"Lyneham","state"=> "ACT","postcode"=> "2602", "country"=> "Australia", "year"=>5, "month"=>1) );
  $five_years_only_current_address = 'yes'; //: this.five_years_current_address,
  $five_years_current_previous_address = 'yes' ;//: this.five_years_previous_address,
  $page_number = 5; // 'page_number': '5'
  $postal_same_address = 'yes';
  $from_info3 = 1;
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

if($from_info3) {
  /* Establish the Database connection to update the address information */
  $con = get_connection_db($login_information, $database_name);
  $street_t = '';
  $suburb_t = '';
  $state_t = '';
  $postcode_t = '';
  $year_t = '';
  $month_t = '';
  $five_years_only_current_address_t = '';
  $five_years_current_previous_address_t = '';
  $postal_same_address_t = '';
  $ref_num_t = '';
  $email_t = '';
  $postal_street_address_t = '';
  $postal_suburb_t = '';
  $postal_state_t = '';
  $postal_postcode_t = '';
  $previous_street_address_t = '';
  $previous_suburb_t = '';
  $previous_state_t = '';
  $previous_postcode_t = '';
  $previous_month_t = '';
  $previous_year_t = '';

  /* If the connection is established */
  $previous_flag = 0;
  $main_flag = 0;
  $postal_flag = 0;

  if($con != NULL) {

    $table_names = array();
    $table_names[] = get_customer_main_address();
    if($postal_same_address == 'no') {
      $table_names[] = get_customer_postal_address();
    } else {
        /* Trigger the deletion of postal address based on the reference num  and email */
        $delete_postal_flag = delete_record($con, get_customer_postal_address(), $ref_num, $email);
        if($delete_postal_flag == 1) {
          $postal_flag = 1;
        }
    }

    /* Delete the previous address list based on the reference number and email  */
    $delete_previous_flag = delete_record($con, get_customer_previous_address(), $ref_num, $email);

    /* Check the count of the previous address before inserting any element in the DB */
    if(count($previous_address) > 0) {
      $table_names[] = get_customer_previous_address();
    } else {
        $previous_flag = 1;
    }

    $update_query = '';
    $result = '';
    // echo json_encode(count($table_names));

    for($i = 0; $i < count($table_names); $i++) {
      if($table_names[$i] == get_customer_main_address() ) {
        $update_query = "UPDATE ".get_customer_main_address()." SET  street_address=?, suburb=?, state=?, postcode=?, year_stay=?, month_stay=?, five_years_only_current_address=?, five_years_current_previous_address=?, postal_same_address=? WHERE reference_num=? AND email=?";
        $result = $con->prepare($update_query);

        if($result != NULL) {
          $result->bind_param("ssssiisssss", $street_t, $suburb_t, $state_t, $postcode_t, $year_t, $month_t, $five_years_only_current_address_t,
                                             $five_years_current_previous_address_t, $postal_same_address_t, $ref_num_t, $email_t);
          $street_t = $street_address;
          $suburb_t = $suburb;
          $state_t = $state;
          $postcode_t = $postcode;
          $year_t = $current_year;
          $month_t = $current_month;
          $five_years_only_current_address_t = $five_years_only_current_address;
          $five_years_current_previous_address_t = $five_years_current_previous_address;
          $postal_same_address_t = $postal_same_address;
          $ref_num_t = $ref_num;
          $email_t = $email;
        } else {
            if($debug) {
              echo json_encode('no-result-addr');
            }
            $error_flag = 1;
            break;
        }
      } else if($table_names[$i] == get_customer_postal_address()) {

          $update_query = "UPDATE ".get_customer_postal_address()." SET  street_address=?, suburb=?, state=?, postcode=? WHERE reference_num=? AND email=?";
          $result = $con->prepare($update_query);

          if($result != NULL) {
            $result->bind_param("ssssss", $street_t, $suburb_t, $state_t, $postcode_t, $ref_num_t, $email_t);
            $street_t = $postal_street_address;
            $suburb_t = $postal_suburb;
            $state_t = $postal_state;
            $postcode_t = $postal_postcode;
            $ref_num_t = $ref_num;
            $email_t = $email;
          } else {
              if($debug) {
                echo json_encode('no-result-addr');
              }
              $error_flag = 1;
              break;
          }

      } else if($table_names[$i] == get_customer_previous_address()) {
          $comma = ',';
          $var_len = get_var_len();
          //echo json_encode($var_len);
          $previous_flag = create_insert_previous($con, $comma, $ref_num, $email, 'PREVIOUS', $previous_address, $var_len);
      }

      if($table_names[$i] != get_customer_previous_address()) {
        $exec = $result->execute();
        if($result->execute()) {
          if($table_names[$i] == get_customer_postal_address() ) {
            $postal_flag = 1;
          } else if ($table_names[$i] == get_customer_main_address()) {
              $main_flag = 1;
          }
        } else if($exec != NULL) {
            if($debug) {
              echo json_encode('execute failure for the postal or main address');
            }
            // $result->close();
            $exec = NULL;
            echo json_encode('ns: 1');
        } else {
            $exec = NULL;
            echo json_encode('ns : 2 ');
        }
      }
      $result->close();
    }
    /* Checking whether all updations have happened properly after running across the tables  */
    if($main_flag == 1 && $previous_flag == 1 && $postal_flag == 1) {
      echo json_encode('success');
    } else {
        echo json_encode('ns');
    }
  } else {
     echo json_encode('conn-fail-addr');
  }

  if($con != NULL) {
    $con->close();
  }
  if($error_flag) {
    if($debug) {
      echo json_encode('error happened during updation of address details');
    }
    echo json_encode('ns');
  }
} else {
      echo json_encode('no-from-info3');
}

?>

