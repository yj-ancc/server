<?php
header('Content-Type', 'application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
// Path name for the login file

function get_connection_db($login_information, $database_name) {

    //file_exists('./../../../'.$login_information) == FALSE
    if(file_exists('/'.$login_information) == FALSE) {
        echo json_encode(file_get_contents($login_information));
        return NULL;
    }
    //$login_information = file_get_contents('./../../../'.$login_information);
    $login_information = file_get_contents('.'.$login_information);

    $request = json_decode($login_information, true);
    $user_name = $request['db']['name'];
    $password = $request['db']['password'];



    //$url = 'localhost';
    $url = '172.31.39.141';

    /* Manually create the customer database and start populating things */
    /* Manually create a login.json file with <username>;<password> */

    $customer_table = 'customer_info';
    $purpose_of_check = 'purpose_of_check';

    $con = mysqli_connect($url, trim($user_name), trim($password), $database_name);
    if (mysqli_connect_errno() ) {
        echo  json_encode("Error inside the mysqli connection: ". mysqli_connect_error());
        return NULL;
    } else {
            return $con;
    }

}

?>
