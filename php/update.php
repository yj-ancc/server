<?php    

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

// header('Content-Type', 'application/json');


function update_information($con, $obj, $purpose,$table_name ) {
  /* Updating the customer table details based on the information provided */
  $update_query = ' ';
  $result = '';

  /* updation query is going to be executed */
  if ($table_name == 'customer_info') {
    $update_query = "UPDATE customer_info SET first_name=?, middle_name=?, last_name=?, email=?, phone_num=?,from_location=?,is_single_name=? WHERE reference_num=?";
    $result = $con->prepare($update_query);
    $result->bind_param("ssssssss", $first_name, $middle_name, $last_name, $email, $phone, $from_location, $is_single_name, $ref_num);
    $first_name =  $obj->first_name;
    $middle_name =  $obj->middle_name;
    $last_name = $obj->last_name;
    $email =  $obj->email;
    $phone = $obj->phone;
    $from_location = $obj->from_location;
    $is_single_name = $obj->is_single_name;
    $ref_num = $obj->ref_no;
  } else if($table_name == 'purpose_of_check') {
      $update_query = "UPDATE purpose_of_check SET email=?, check_type=?, apply_for_student=?, position_title_occupation=?, proposed_place_of_work=?, location_of_work=?, vul_ppl=? WHERE reference_num=?";
      $result = $con->prepare($update_query);
      $result->bind_param("ssisssss", $email, $check_type, $apply_for_student, $position_title_occupation, $proposed_place_of_work, $location_of_work, $vul_ppl, $ref_num);
      $email =  $purpose->email;
      $check_type =  $purpose->check_type;
      $apply_for_student =  $purpose->apply_for_student;
      $position_title_occupation = $purpose->position;
      $proposed_place_of_work = $purpose->place_work;
      $location_of_work = $purpose->loc;
      $vul_ppl =  $purpose->vul_ppl;
      $ref_num =  $purpose->ref_no;
  } else {
      echo json_encode('table name not valid in updated.php');
      return NULL;
  }
  /* Run the updation query */
  if($result->execute()) {
      $result->close();
      return $ref_num;
  } else if($result != NULL) {
      echo json_encode('Updation failure');
      $result->close();
      return NULL;
  } else {
      return NULL;
  }

}
?>
