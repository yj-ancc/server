<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

header('Content-Type', 'application/json');


// Fetching the customer table informations based on the reference number
function fetch_table_customer($con, $ref_num) {
    $select_query = "SELECT  first_name, middle_name, last_name, email, phone_num, started_date FROM customer_info WHERE reference_num='".$ref_num."'";

    /* Executing the select statement : checking the working of select statement */
    $result = $con->query($select_query);
    $result_string = '';
    /* Checking for the execution status of the select statement */
    if($result == FALSE) {
        echo json_encode('executing select statement failure inside fetch.php');
        return;

    } else if ($result) {
       /* If the select statement is executed, the retrieve the email and phone number */
       $cr = 0;
       $row = mysqli_fetch_assoc($result);
       if($row) {
            $first_name = $row['first_name'];
            $middle_name = $row['middle_name'];
            $last_name = $row['last_name'];
            $email = $row['email'];
            $phone_num = $row['phone_num'];
            $started_date = $row['started_date'];
            $result_string = $first_name.'&'.$middle_name.'&'.$last_name.'&'.$email.'&'.$phone_num.'&'.$started_date;
            return $result_string;
        } else {
        // If the result statement is not working.
        // Error conditions to be added later.
        }
    }
}

// Fetching the purpose of check table informations based on the reference number
function fetch_table_purp($con, $ref_num) {
    $select_query = "SELECT check_type, position_title_occupation, proposed_place_of_work, location_of_work, vul_ppl FROM purpose_of_check WHERE reference_num='".$ref_num."'";

    /* Executing the select statement : checking the working of select statement */
    $result = $con->query($select_query);
    $result_string = '';

    /* Checking for the execution status of the select statement */
    if($result == FALSE) {
        echo json_encode('executing select statement failure inside fetch.php');
        return;

    } else if ($result) {
       /* If the select statement is executed, the retrieve the email and phone number */
       $cr = 0;
       $row = mysqli_fetch_assoc($result);
       if($row) {
            $check_type = $row['check_type'];
            $position_title_occupation = $row['position_title_occupation'];
            $proposed_place_of_work = $row['proposed_place_of_work'];
            $location_of_work = $row['location_of_work'];
            $vul_ppl = $row['vul_ppl'];
            $result_string = $check_type.'&'.$position_title_occupation.'&'.$proposed_place_of_work.'&'.$location_of_work.'&'.$vul_ppl;
            return $result_string;
        } else {
        // If the result statement is not working.
        // Error conditions to be added later.
        }
    }

}


?>
