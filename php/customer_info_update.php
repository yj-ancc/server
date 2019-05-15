<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


include_once 'login.php';
include_once 'names.php';


$database_name = get_db_name();
$login_information =  '/'.get_login_file();

/* Retrieve all the contents from the info page for updating the values */
$post_data = file_get_contents("php://input");
// Decoding the json data to retrieve based on objects
$request = json_decode($post_data, true);

/* Retrieve all the details from the angular service file */
$type =  $request['type'];
$ref_num =  $request['ref_num'];
/* Setting up the values of the first, middle and last name */
$first_name = '';
$middle_name = '';
$last_name = '';
$is_single_name = '';
$gender = '';
$dob = '';
$country_born = '';
$state_born = '';
$city_born = '';
$page_number = '';

$debug = 0;

if($debug) {
  $type = 'update';
}

if($type == 'update') {
  /* Development purpose */
  if($debug) {
    $first_name = 'Sarveshwaran';
    $middle_name = '';
    $last_name = 'Rajarajan';
    $is_single_name = 'yes';
    $gender = 'male';
    $dob = '17-12-1991';
    $country_born = 'Australia';
    $state_born = 'Australian Capital Territory';
    $city_born = 'Canberra';
    $page_number = '4';
    $ref_num =  '5c4942745d52f';

  } else {
      $first_name = $request['first_name'];
      $middle_name = $request['middle_name'];
      $last_name = $request['last_name'];
      $is_single_name = $request['is_single_name'];
      $gender = $request['gender'];
      $dob = $request['dob'];
      $country_born = $request['country_born'];
      $state_born = $request['state_born'];
      $city_born = $request['city_born'];
      $page_number = $request['page_number'];
  }

}  else {
    echo json_encode('info-no-type');
    return;
}


/* Establish the connection with the SQL database */
$con = get_connection_db($login_information, $database_name);

/* If the connection is established */
if($con != NULL) {
    $update_query = "UPDATE ".get_sec_customer_table_name()." SET first_name=?, middle_name=?, last_name=?, is_single_name=?, dob=?, gender=?, state_born=?, country_born=? WHERE reference_num=?";
    $result = $con->prepare($update_query);
    if($result != NULL ) {
      $result->bind_param("sssssssss", $f_name, $m_name, $l_name, $is_s_name, $t_dob, $t_g, $t_sb, $t_cb, $ref);
      $f_name = ($first_name);
      $m_name = ($middle_name);
      $l_name = ($last_name);
      $is_s_name = ($is_single_name);
      $t_dob = $dob;
      $t_g = $gender;
      $t_sb = $state_born;
      $t_cb = $country_born;
      $ref = ($ref_num);

      if($result->execute()) {
        $result->close();
        /* Gets inside this function only based on the page number initialisation */
        /* Updating the page number of the application in the customer_main DB */
        $update_page_number = 'UPDATE '.get_main_customer_table_name().' SET page_completed=? WHERE reference_num=?';
        $result_page_number = $con->prepare($update_page_number);
        if($result_page_number != NULL) {
          $result_page_number->bind_param('ss', $page, $ref);
          $page = $page_number;
          $ref = $ref_num;
        }
        /* Setting the page number based on the info page  */
        if( $result_page_number->execute() ) {
          $result_page_number->close();
          echo json_encode('success');

        } else if( $result_page_number != '') {
            $result_page_number->close();
            echo json_encode('fail:info-no-exec');
        } else {
            echo json_encode('fail:info-no-res');
        }
      } else if ($result != '') {
          $result->close();
          echo json_encode('fail:update-info-1');
      } else {
          echo json_encode('fail:no update');
        }
    } else {
        echo json_encode('info-fail-update');
    }
    /* closing the database connection after finishing all the operations concerned */
    $con->close();
} else {
    echo json_encode('info-fail-conn');
}



?>
