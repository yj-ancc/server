<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
include 'login.php';
include 'names.php';
$login_information =  "/login.json";
$database_name = "ancc";


function insert_smartid($con, $data, $login_information, $database_name){

	$smartid = $data['smartid'];
  for( $i = 0; $i< count($smartid); $i++ ) {

    $insert_prepare = $con->prepare("INSERT INTO smartid (smart_id, reference_number, email, upload_method, document_category) VALUES (?,?,?,?,?)");
    $insert_prepare->bind_param("sssss", $smart_id, $reference_number, $email, $upload_method, $document_category);

    $smart_id = $data['reference_number'].'#'.$smartid[$i]['document_category'];
    $reference_number = $data['reference_number'];
    $email = $data['email'];
    $upload_method = $smartid[$i]['upload_method'];
    $document_category = $smartid[$i]['document_category'];

      if ($insert_prepare != '' && $insert_prepare->execute()) {
          // echo json_encode("insert smartid table success");
          $insert_prepare->close();
      } else {
          // echo json_encode("insert smartid table failed");
          if ($insert_prepare != '') $insert_prepare->close();
          return -1;
      }
  }
    return 0;
}


function insert_previous_name($con, $data, $login_information, $database_name){

  $pn = $data['previous_name'];


              $insert_prepare = $con->prepare("INSERT INTO smartid_previous_name (smart_id, document_id, document_name, num) VALUES (?,?,?,?)");
              $insert_prepare->bind_param("sssi", $smart_id, $document_id, $document_name, $num);

              $smart_id = $data['reference_number'].'#'.'previous_name';
              $document_name = 'change of name';
              $document_id = $smart_id.'#'.'change_of_name';
              
              $temp = 0;
              for($i = 0; $i< count($pn); $i++){
                if($pn[$i]['type']=='change_of_name'){
                  $temp=$temp+1;
                }
              }
              $num = $temp;

              if ($insert_prepare != '' && $insert_prepare->execute()) {
                  // echo json_encode("insert smartid table success");
                  $insert_prepare->close();
              } else {
                // echo json_encode("insert smartid table failed");
              if ($insert_prepare != '') $insert_prepare->close();
                  return -1;
              } 


              $insert_prepare = $con->prepare("INSERT INTO smartid_previous_name (smart_id, document_id, document_name, num) VALUES (?,?,?,?)");
              $insert_prepare->bind_param("sssi", $smart_id, $document_id, $document_name, $num);

              $smart_id = $data['reference_number'].'#'.'previous_name';
              $document_name = 'marriage certificate';
              $document_id = $smart_id.'#'.'marriage_certificate';
              $temp = 0;
              for($i = 0; $i< count($pn); $i++){
                if($pn[$i]['type']=='marriage_certificate'){
                  $temp=$temp+1;
                }
              }
              $num = $temp;

              if ($insert_prepare != '' && $insert_prepare->execute()) {
                  // echo json_encode("insert smartid table success");
                  $insert_prepare->close();
              } else {
                // echo json_encode("insert smartid table failed");
              if ($insert_prepare != '') $insert_prepare->close();
                  return -1;
              } 
 


    for( $i = 0; $i<count($pn); $i++ ) {
          foreach($smartid[$i]['details'] as $key=>$value){

              $insert_prepare = $con->prepare("INSERT INTO smartid_document_details (document_id, title, value) VALUES (?,?,?)");
              $insert_prepare->bind_param("sss", $document_id, $title, $value);

              $smart_id = $data['reference_number'].'#'.'previous_name';
              $document_id = $smart_id.'#'.$pn[$i]['type'].'#'.($i+1);
              $title = $key;
              $value = $value;

              if ($insert_prepare != '' && $insert_prepare->execute()) {
                  // echo json_encode("insert smartid table success");
                  $insert_prepare->close();
              } else {
                // echo json_encode("insert smartid table failed");
              if ($insert_prepare != '') $insert_prepare->close();
                  return -1;
              } 

          }


    }   


        $previous_name_count = 0;
        $marriage_certificate_count = 0;
        
        for( $i = 0; $i< count($pn); $i++ ) {

              $base64 = $pn[$i]['front']['data'];  
              $path = get_invoice_file_path().'/'.$data['reference_number'];
              $link_path = './../data/'.$data['reference_number'];

             $insert_prepare = $con->prepare("INSERT INTO smartid_file (id, name, link, type) VALUES (?,?,?,?)");
             $insert_prepare->bind_param("ssss",$id, $name, $link, $type);

              $smart_id = $data['reference_number'].'#'.'previous_name';
              $temp_num = 0;
              if($pn[$i]['type'] == 'change_of_name'){
                $temp_num = $previous_name_count;
                $previous_name_count = $previous_name_count+1;
              } else {
                $temp_num = $marriage_certificate_count;
                $marriage_certificate_count = $marriage_certificate_count + 1;
              }

              $id = $smart_id.'#'.$pn[$i]['type'].'#'.($temp_num+1);
              $type = $pn[$i]['front']['type'];
              $name = '';
              $link = $link_path.'/'.$id.'.txt';


            if ($insert_prepare != '' && $insert_prepare->execute()) {
                // echo json_encode("insert file table front page success");
                $insert_prepare->close();
            } else {
                // echo json_encode("insert file table front page failed");
                if ($insert_prepare != '') $insert_prepare->close();
                return -2;
            } 
              //save file to txt
    if (mkdir($path, 0777, true) ) {

      $myfile = fopen($path.'/'.$id.'.txt', "w") or die("Unable to write file!");
      fwrite($myfile, $base64);
      fclose($myfile);
    } else {
      $myfile = fopen($path.'/'.$id.'.txt', "w") or die("Unable to write file!");
      fwrite($myfile, $base64);
      fclose($myfile);
      /* If the necessary file is not created */
        // echo json_encode('NC');
    }
  }
  return 0;
}


function insert_document_details($con, $data,$login_information, $database_name){
	$smartid = $data['smartid'];

	for( $i = 0; $i< count($smartid); $i++ ) {
		foreach($smartid[$i]['details'] as $key=>$value) {
    			$insert_prepare = $con->prepare("INSERT INTO smartid_document_details (document_id, title, value) VALUES (?,?,?)");
    			$insert_prepare->bind_param("sss",$document_id, $title, $value);

    			$smart_id = $data['reference_number'].'#'.$smartid[$i]['document_category'];
    			$document_id = $smart_id.'#'.$smartid[$i]['document_name'];
    			$title = $key;
    			$value = $value;

  			if ($insert_prepare != '' && $insert_prepare->execute()) {
    			$insert_prepare->close();
    			// echo json_encode('insert document_detail table success');
  			} else  if ($insert_prepare != '') {
      			$insert_prepare->close();
      			// echo json_encode('insert document_detail table insert_prepare_null');
      			return -1;
  			} else {
      			// echo json_encode('insert document_detail table failed');
      			return -1;
  			}
		}
  }
  return 0;
}

function insert_document($con, $data,$login_information, $database_name) {
	$smartid = $data['smartid'];
	for( $i = 0; $i< count($smartid); $i++ ) {


			if($smartid[$i]['upload_method'] == 'webcam'){

				$query = "INSERT INTO smartid_webcam (smart_id, document_name, document_id, front, back) VALUES (?,?,?,?,?)";
    			$insert_prepare = $con->prepare($query);
    			$insert_prepare->bind_param("sssss", $smart_id, $document_name, $document_id, $front, $back);
    			$smart_id = $data['reference_number'].'#'.$smartid[$i]['document_category'];
    			$document_name = $smartid[$i]['document_name'];
    			$document_id = $smart_id.'#'.$smartid[$i]['document_name'];
    			$front = 'F#'.$smart_id.'#'.$smartid[$i]['document_name'];
    			$back = 'B#'.$smart_id.'#'.$smartid[$i]['document_name'];


			} else if ($smartid[$i]['upload_method'] =='offline'){
          $query = "INSERT INTO smartid_scan (smart_id, document_name, document_id, front, back) VALUES (?,?,?,?,?)";
          $insert_prepare = $con->prepare($query);
          $insert_prepare->bind_param("sssss", $smart_id, $document_name, $document_id, $front, $back);
          $smart_id = $data['reference_number'].'#'.$smartid[$i]['document_category'];
          $document_name = $smartid[$i]['document_name'];
          $document_id = $smart_id.'#'.$smartid[$i]['document_name'];
          $front = 'F#'.$smart_id.'#'.$smartid[$i]['document_name'];
          $back = 'B#'.$smart_id.'#'.$smartid[$i]['document_name'];

      } else if ($smartid[$i]['upload_method'] == 'post'){

				$insert_prepare = $con->prepare("INSERT INTO smartid_post (smart_id, document_name, document_id, download_generated, downloaded_status, document_category, front, back) VALUES (?,?,?,?,?,?,?,?)");
    			$insert_prepare->bind_param("ssssssss", $smart_id, $document_name, $document_id, $download_generated, $downloaded_status, $document_category, $front, $back);

    			$smart_id =  $data['reference_number'].'#'.$smartid[$i]['document_category'];
    			$document_name = $smartid[$i]['document_name'];
    			$document_id = $smart_id.'#'.$smartid[$i]['document_name'];
    			$download_generated =$data['smartid_post']['download_generated'];
				$downloaded_status = $data['smartid_post']['downloaded_status'];
    			$document_category =  $smartid[$i]['document_category'];
    			$front = 'F#'.$smart_id.'#'.$smartid[$i]['document_name'];
    			$back = 'B#'.$smart_id.'#'.$smartid[$i]['document_name'];

			} else if ($smartid[$i]['upload_method'] == 'phone'){

				 $insert_prepare = $con->prepare("INSERT INTO smartid_phone (smart_id, document_name, document_id, mobile_num, message_sent, send_link_count, document_category, front, back) VALUES (?,?,?,?,?,?,?,?,?)");
    			$insert_prepare->bind_param("sssssssss", $smart_id, $document_name, $document_id, $mobile_num, $message_sent,$send_link_count, $document_category, $front, $back);

    			$smart_id =  $data['reference_number'].'#'.$smartid[$i]['document_category'];
    			$document_name = $smartid[$i]['document_name'];
    			$document_id = $smart_id.'#'.$smartid[$i]['document_name'];
				$mobile_num = $data['smartid_phone']['mobile_num'];
				$message_sent =$data['smartid_phone']['message_sent'];
				$send_link_count = $data['smartid_phone']['send_link_count'];
				$document_category =  $smartid[$i]['document_category'];
    			$front = 'F#'.$smart_id.'#'.$smartid[$i]['document_name'];
    			$back = 'B#'.$smart_id.'#'.$smartid[$i]['document_name'];

			}

      if ($insert_prepare != '' && $insert_prepare->execute()) {
          // // echo json_encode("insert document table success");
          $insert_prepare->close();
      } else {
          echo json_encode("failed");
          $insert_prepare->close();
          if ($insert_prepare != '') $insert_prepare->close();
          return -1;
      }
	}
	return 0;

}


function insert_file($con, $data,$login_information, $database_name){
	$smartid = $data['smartid'];

	for( $i = 0; $i< count($smartid); $i++ ) {


		$base64 = $smartid[$i]['front']['data'];	
		$path = get_invoice_file_path().'/'.$data['reference_number'];
		$link_path = './../data/'.$data['reference_number'];



		$insert_prepare = $con->prepare("INSERT INTO smartid_file (id, name, link, type) VALUES (?,?,?,?)");
    	$insert_prepare->bind_param("ssss",$id, $name, $link, $type);

    	$smart_id = $data['reference_number'].'#'.$smartid[$i]['document_category'];
    	$id = 'F#'.$smart_id.'#'.$smartid[$i]['document_name'];
    	$type = $smartid[$i]['front']['type'];
    	$name = $smartid[$i]['front']['name'];
    	$link = $link_path.'/'.$id.'.txt';


      if ($insert_prepare != '' && $insert_prepare->execute()) {
          // // echo json_encode("insert file table front page success");
          $insert_prepare->close();
      } else {
          // echo json_encode("insert file table front page failed");
          if ($insert_prepare != '') $insert_prepare->close();
          return -2;
      }
            	//save file to txt
		  if (mkdir($path, 0777, true) ) {
        $myfile = fopen($path.'/'.$id.'.txt', "w") or die("Unable to write file!");
			  fwrite($myfile, $base64);
			  fclose($myfile);
		  } else {
			    $myfile = fopen($path.'/'.$id.'.txt', "w") or die("Unable to write file!");
			    fwrite($myfile, $base64);
			    fclose($myfile);
    	    /* If the necessary file is not created */
    		  // echo json_encode('NC');
	  	}


		$base64_b = $smartid[$i]['back']['data'];

    if($base64_b!=null && $base64_b!=''){

      $insert_prepare = $con->prepare("INSERT INTO smartid_file (id, name, link, type) VALUES (?,?,?,?)");
      $insert_prepare->bind_param("ssss",$id_b, $name_b, $link_b, $type_b);

      $smart_id = $data['reference_number'].'#'.$smartid[$i]['document_category'];
      $id_b = 'B#'.$smart_id.'#'.$smartid[$i]['document_name'];
      $type_b = $smartid[$i]['back']['type'];
      $name_b = $smartid[$i]['back']['name'];
      $path.'/'.$id.'.txt';
      $link_b = $link_path.'/'.$id_b.'.txt';

      if ($insert_prepare != '' && $insert_prepare->execute()) {
          // // echo json_encode("success");
          $insert_prepare->close();
      } else {
          // // echo json_encode("success");
          if ($insert_prepare != '') $insert_prepare->close();
          return -1;
      }


      //save file to txt
    if (mkdir($path, 0777, true) ) {

       $myfile_b = fopen($path.'/'.$id_b.'.txt', "w") or die("Unable to write file!");
      fwrite($myfile_b, $base64_b);
      fclose($myfile_b);
    } else {
      $myfile_b = fopen($path.'/'.$id_b.'.txt', "w") or die("Unable to write file!");
      fwrite($myfile_b, $base64_b);
      fclose($myfile_b);
      /* If the necessary file is not created */
      // echo json_encode('NC');
    }
    }
    }
    return 0;
}


function update_values($con, $page_number, $reference_number) {
   // updating the page number in the main table
   $update_page_number = 'UPDATE '.get_main_customer_table_name().' SET page_completed=? WHERE reference_num=?';
   // echo json_encode($update_page_number);
   $result_page_number = $con->prepare($update_page_number);
   if($result_page_number != NULL) {
     $result_page_number->bind_param('ss', $page, $ref);
     $page = $page_number;
     $ref = $reference_number;
   }
   /* Setting the page number based on the smartid2 page  */
   if( $result_page_number != '' && $result_page_number->execute() ) {
     $result_page_number->close();
     return 1;
   } else if( $result_page_number != '') {
       $result_page_number->close();
   }
   return -1;
}



// Main function for collecting the informations
$post_data = file_get_contents("php://input");
// Decoding the json data to retrieve based on objects
$request = json_decode($post_data, true);
$reference_number = $request["reference_number"];

// check for the reference number
if ($reference_number == '') {
  echo json_encode('no reference number');
  return;
}

// customer object within the json is accessed in here
if($request != NULL){
  // establishing the DB connection with the login credentials present
   if(($con = get_connection_db($login_information, $database_name)) != NULL ) {
    mysqli_autocommit($con,true);
		$insertion_smartid = insert_smartid($con, $request,$login_information, $database_name);
   		if($insertion_smartid == 0){
   			$insertion_document_details = insert_document_details($con, $request,$login_information, $database_name);
   			if($insertion_document_details == 0){
   				$insertion_file = insert_file($con, $request,$login_information, $database_name);
   				if($insertion_file == 0){
   					$insertion_document = insert_document($con, $request,$login_information, $database_name);
   					if($insertion_document == 0) {
              $insertion_previous_name = insert_previous_name($con, $request,$login_information, $database_name);
              if($insertion_previous_name == 0) {
                //mysqli_commit($con);
                //mysqli_close($con);
                $update_page_number = update_values($con, '9', $reference_number);

                if ($update_page_number == 1) {
                  echo json_encode('success');
                } else {
                    echo json_encode('page number insertion failure');
                }
              }
   					} else {
   					  echo json_encode('previous name fail');
   					}
   				}
   			} else {
   			  echo json_encode('document detail failure');
   			}
   		} else {
   		    echo json_encode('smartid failure');
   	}

   $con->close();
   } else {
   	echo json_encode('connection failure');
   }
}


?>
