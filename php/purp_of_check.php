<?php
include 'names.php';

header('Access-Control-Allow-Origin: '.get_server_det());
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


function get_connection_dbs($login_information, $database_name) {

    if(file_exists(get_login_path().''.$login_information) == FALSE) {
        echo json_encode(file_get_contents($login_information));
        return NULL;
    }
    $login_information = file_get_contents(get_login_path().''.$login_information);

    $request = json_decode($login_information, true);
    $user_name = $request['db']['name'];
    $password = $request['db']['password'];

    $url = name_of_server();

    /* Manually create the customer database and start populating things */
    /* Manually create a login.json file with <username>;<password> */

    $purpose_of_check = get_purpose_of_check();

    $con = mysqli_connect($url, trim($user_name), trim($password), $database_name);
    if (mysqli_connect_errno() ) {
        echo  json_encode("Error inside the mysqli connection: ". mysqli_connect_error());
        return NULL;
    } else {
        return $con;
    }

}

$con = get_connection_dbs(get_login_file(), get_db_name());

if($con == NULL) {
    echo json_encode(' connection not established ! ');
    return NULL;
}

// Main function for collecting the informations
$post_data = file_get_contents("php://input");
// Decoding the json data to retrieve based on objects
$request = json_decode($post_data, true);
// customer object within the json is accessed in here
$ref_num = $request['ref_num'];

if($ref_num == '') {
  echo json_encode('reference number empty');
  $con->close();
  return NULL;
}

$sql = "SELECT check_type, position_title_occupation, proposed_place_of_work, vul_ppl FROM ".get_purpose_of_check()." WHERE reference_num='".$ref_num."'";

/* Executing the select statement : checking the working of select statement */
$result = $con->query($sql);

/* Checking for the execution status of the select statement */
if($result == FALSE) {
    echo json_encode('executing select statement failure inside purp_of_check.php');
    $con->close();
    return;

} else if ($result) {
   $cr = 0;
   $row = mysqli_fetch_assoc($result);
   if($row) {
        $check_type = $row['check_type'];
        $position_title_occupation = $row['position_title_occupation'];
        $proposed_place_of_work = $row['proposed_place_of_work'];
        $vul_ppl = $row['vul_ppl'];
        $ref_num = $row['ref_num'];
        echo json_encode($check_type.'&'.$position_title_occupation.'&'.$proposed_place_of_work.'&'.$vul_ppl."&".$ref_num);
    } else {
        echo json_encode('NS');
        $con->close();
        return NULL;
    }
}
$con->close();
?>


