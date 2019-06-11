<?php
header('Content-Type', 'application/json');
header('Access-Control-Allow-Origin: '.get_server_det());
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
// Path name for the login file

function get_connection_db($login_information, $database_name) {


    //$login_information = json_decode($login_information, true);

    if(file_exists(get_login_path().''.$login_information) == FALSE) {
        echo json_encode('NOT FOUND');
        return NULL;
    }
    $login_information = file_get_contents(get_login_path().''.$login_information);
    // echo json_encode($login_information);

    $request = json_decode($login_information, true);
    $user_name = $request['db']['name'];
    $password = $request['db']['password'];

    $url = name_of_server();

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
