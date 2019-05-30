<?php

//include_once 'customer_info.php';

header('Content-Type', 'application/json');

function query_information($con, $obj, $purpose, $table_name, $db_name) {

    $create_query = ' ';
    $customer_info_param = "(reference_num VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, middle_name VARCHAR(255), last_name VARCHAR(255), email VARCHAR(255) NOT NULL, page_completed INT NOT NULL, phone_num VARCHAR(255), started_date VARCHAR(50), from_location VARCHAR(255), is_single_name VARCHAR(255), PRIMARY KEY (reference_num)) ";
    $purp_of_check = "(reference_num VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, check_type VARCHAR(255) NOT NULL, apply_for_student INT, position_title_occupation VARCHAR(255) NOT NULL, proposed_place_of_work VARCHAR(255) NOT NULL, location_of_work VARCHAR(255), purpose_id VARCHAR(255) NOT NULL PRIMARY KEY, vul_ppl VARCHAR(255) NOT NULL,  FOREIGN KEY (reference_num) REFERENCES ".get_customer_table_name()."(reference_num)  ON UPDATE CASCADE )";

    if ($table_name == 'customer_info') {
        $create_query = "CREATE TABLE IF NOT EXISTS customer_info ".$customer_info_param;
    }  else if($table_name == 'purpose_of_check') {
        $create_query = "CREATE TABLE IF NOT EXISTS  purpose_of_check ".$purp_of_check;
    }  else return 0;

    // Creation query has to be from one of the table create commands.
    // If not, then return it without executing any mysql commands.
    if($create_query == ' ') return 0;

    // Insert elements into the database
    $insert_query = '';
    $insert_prepare = '';

    if($con->query($create_query) == TRUE){

        if ( $table_name == 'customer_info' ) {

            /* Mysql : prepared statement method to access the local database
             * Binding the values at the run time to avoid sql injection
             * Storing all the customer related information into the customer database
             */
            $insert_prepare = $con->prepare ("INSERT INTO customer_info (reference_num, first_name, middle_name, last_name, email, page_completed, phone_num, started_date, from_location, is_single_name) VALUES ( ?, ?,  ?, ?, ?, ?, ?, ?, ?, ?) ");
            $insert_prepare->bind_param("sssssissss", $ref_num, $first_name, $middle_name, $last_name, $email, $page_completed, $phone, $date, $from_location, $is_single_name);
            $ref_num = $obj->ref_no;
            $first_name =  $obj->first_name;
            $middle_name =  $obj->middle_name;
            $last_name = $obj->last_name;
            $email =  $obj->email;
            $page_completed = $obj->page_completed;
            $phone = $obj->phone;
            $date = $obj->date_val;
            $from_location = $obj->from_location;
            $is_single_name = $obj->is_single_name;

        }   else if ( $table_name == 'purpose_of_check' ) {

            /* Storing Purpose of check related information into the customer database */
            $insert_prepare = $con->prepare ("INSERT INTO purpose_of_check (reference_num, email, check_type, apply_for_student, position_title_occupation, proposed_place_of_work,  location_of_work, purpose_id, vul_ppl   ) VALUES ( ?, ?, ?, ?, ?, ?, ?,  ?, ? ) " );
            $insert_prepare->bind_param("sssisssss", $ref_num,  $email, $check_type, $apply_for_student, $position_title_occupation, $proposed_place_of_work, $location_of_work, $purpose_id, $vul_ppl);

            $ref_num =  $purpose->ref_no;
            $email =  $purpose->email;
            $check_type =  $purpose->check_type;
            $apply_for_student =  $purpose->apply_for_student;
            $position_title_occupation = $purpose->position;
            $proposed_place_of_work = $purpose->place_work;
            $location_of_work = $purpose->loc;
            $purpose_id =  $purpose->id;
            $vul_ppl =  $purpose->vul_ppl;
        } else {
            return 0;

        }

        /* Executing the mysql execution statement */
        if ($insert_prepare != '' && $insert_prepare->execute()) {
            $insert_prepare->close();
            return 1;
        } else {
            if ($insert_prepare != '') $insert_prepare->close();
            return 0;
        }

    } else {
        return 0;
    }
}

?>
