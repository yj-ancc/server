<?php
// header('Content-Type', 'application/json');
include_once 'login.php';
include_once 'creating_insert.php';
include_once 'setupvalues.php';
include_once 'update.php';
include_once 'names.php';

$login_information =  "/login.json";
class Customer {
    public $first_name = '';
    public $last_name = '';
    public $middle_name = '';
    public $phone = '';
    public $email = '';
    public $page_completed = '';
    public $date_val = '';
    public $ref_no = '';
    public $from_location = '';
    public $is_single_name = '';
    //  Defining the constructor taking in the customer values
    function Customer($first_name,
                      $middle_name,
                      $last_name,
                      $email,
                      $phone,
                      $page_completed,
                      $date_val,
                      $ref_no,
                      $from_location,
                      $is_single_name,
                      $edit) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->middle_name = $middle_name;
        $this->email = $email;
        $this->phone = $phone;
        $this->edit = $edit;
        $this->page_completed = $page_completed;
        $this->date_val  = $date_val;
        if(!$this->edit) {
         $this->ref_no = uniqid();
        }
        else {
          $this->ref_no = $ref_no;
        }
        $this->from_location = $from_location;
        $this->is_single_name = $is_single_name;
    }
    // Retrieving the first name of the customer
    function get_first_name() {
        return $this->first_name;
    }
    // Retrieving the middle name of the customer
    function get_middle_name() {
        return $this->middle_name;
    }
    // Retrieving the last name of the customer
    function get_last_name() {
        return $this->last_name;
    }
    function get_ref_no() {
        return $this->ref_no;
    }
    function get_from_location() {
        return $this->from_location;
    }
    function get_is_single_name() {
        return $this->is_single_name;
    }
    function get_email() {
        return $this->email;
    }
    // Print function for accessing the basic customer information
    // Log purpose
    function print_rw() {
        return json_encode($this->get_first_name().''.$this->get_middle_name().''.$this->get_last_name());
    }
}
class PurposeOfCheck extends Customer {
    public $check_type = '';
    public $place_work = '';
    public $position = '';
    public $vul_ppl = '';
    public $loc= '';
    public $loc_1 = '';
    public $loc_2 = '';
    public $ref_no = '';
    public $email = '';
    public $id = '';
    //  Defining the constructor taking in the customer values
    function PurposeOfCheck($check_type,
                            $place_work,
                            $position,
                            $vul_ppl,
                            $loc,
                            $loc_1,
                            $loc_2,
                            $apply_for_student,
                            $id,
                            $ref_no,
                            $email)  {
        $this->check_type = $check_type;
        $this->place_work = $place_work;
        $this->position = $position;
        $this->vul_ppl = $vul_ppl;
        $this->loc = $loc;
        $this->loc_1 = $loc_1;
        $this->loc_2  = $loc_2;
        $this->id = $id;
        $this->email = $email;
        $this->ref_no = $ref_no;
        $this->apply_for_student = $apply_for_student;
    }
    function get_apply_for_student() {
        return $this->apply_for_student;
    }
    function get_id() {
        return 'p_'.$this->ref_no;
    }
    // Retrieving the first name of the customer
    function get_check_type() {
        return $this->check_type;
    }
    // Retrieving the middle name of the customer
    function get_place_of_work() {
        return $this->place_work;
    }
    // Retrieving the last name of the customer
    function get_position() {
        return $this->position;
    }
    function get_vul_ppl(){
        return $this->vul_ppl;
    }
    function get_loc(){
        return $this->loc;
    }
    function get_loc_1(){
        return $this->loc_1;
    }
    function get_loc_2(){
        return $this->loc_2;
    }
    function get_email() {
        return $this->email;
    }
    function get_ref_no() {
        return $this->ref_no;
    }
    // Print function for accessing the basic customer information
    // Log purpose
    function print_rw() {
        return json_encode($this->get_email().''.$this->get_loc().''.$this->get_loc_1());
    }
}


// Main function for collecting the informations
$post_data = file_get_contents("php://input");
// Decoding the json data to retrieve based on objects
$request = json_decode($post_data, true);
// customer object within the json is accessed in here
$customer_information = $request['customer'];
// purpose of check object accessed within the json object passed in from the service.ts file
$purpose_of_check = $request['purpose_of_check'];
// Populate the customer object with the informations given.
$customer_obj = new Customer($customer_information['first_name'],
                             $customer_information['middle_name'],
                             $customer_information['last_name'],
                             $customer_information['email'],
                             $customer_information['phone'],
                             $customer_information['page_completed'],
                             $customer_information['date_val'],
                             $customer_information['ref_no'],
                             $customer_information['from_location'],
                             $customer_information['is_single_name'],
                             $customer_information['edit']);
$purpose_of_check = new PurposeOfCheck($purpose_of_check[check_type],
                                       $purpose_of_check[place_work],
                                       $purpose_of_check[position],
                                       $purpose_of_check[vul_ppl],
                                       $purpose_of_check[loc],
                                       $purpose_of_check[loc_1],
                                       $purpose_of_check[loc_2],
                                       $purpose_of_check[apply_for_student],
                                       'pp_'.$customer_obj->get_ref_no(),
                                       $customer_obj->get_ref_no(),
                                       $customer_obj->get_email());
// database connection getting enabled for local storage
//CREATE DATABASE IF NOT EXISTS DBname
$database_name = get_db_name();
$ref_no = '';
$valid_update = 0;
if(($con = get_connection_db($login_information, $database_name)) != NULL ) {
    /* Querying to do the creating and inserting informations into the table */
    if( $customer_information['edit'] ) {
       /* Update  query is triggered with edited functionalities */
       if(($ref_num = update_information ($con, $customer_obj, '', 'customer_info'))) {
         // Valid pass
         $valid_update = 1;
       } else {
           echo json_encode('no-success:update query is failing inside customer table information...');
       }
       if(($ref_num = update_information($con, '', $purpose_of_check, 'purpose_of_check'))) {
         /* After a successful updation of customer and purpose of check table */
         if($valid_update) {
             echo json_encode($ref_num);
         } else {
             echo json_encode('no-success:invalid valid update in customer_info.php');
         }
       } else {
           echo json_encode('no-success:Update query is failing inside purp of check table');
       }
    } else {
        if(query_information($con, $customer_obj, '', 'customer_info', $database_name)) {
          /* Creating the table and storing the relevant information */
           $ref_no = $customer_obj->get_ref_no();
          //$con->close();
        } else {
            echo json_encode("no-success:failed due to ");
        }
        if(query_information($con, '', $purpose_of_check, 'purpose_of_check', $database_name)) {
            // Creating the table and storing the relevant information
            $con->close();
            echo json_encode($ref_no);
        } else {
            echo json_encode("no-success:failed in purp of check");
        }
    }
} else echo json_encode('no-success:connection failure!');
// Storing the information in the database
?>
