<?php

include_once 'names.php';
header('Access-Control-Allow-Origin: '.get_server_det());
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


function get_connection_dbs($login_information, $database_name) {


    //$login_information = json_decode($login_information, true);

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

    $customer_table = get_customer_table_name();
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

$ref_num = $_GET['ref_no'];
// echo json_encode($ref_num);

$sql = "SELECT first_name, middle_name, last_name, email, phone_num FROM customer_info WHERE reference_num='".$ref_num."'";

/* Executing the select statement : checking the working of select statement */
$result = $con->query($sql);

/* Checking for the execution status of the select statement */
if($result == FALSE) {
    echo json_encode('executing select statement failure inside setupvalues.php');
    return;

} else if ($result) {
   /* If the select statement is executed, the retrieve the email and phone number */
   $cr = 0;
   $row = mysqli_fetch_assoc($result);
   if($row) {
        $first_name = $row['first_name'];
        $middle_name = $row['middle_name'];
        $last_name = $row['last_name'];
        $email_id = $row['email'];
        $phone_num = $row['phone_num'];
        echo json_encode($first_name.'&'.$middle_name.'&'.$last_name.'&'.$email_id."&".$phone_num);
    } else {

    }
}
$con->close();
?>

