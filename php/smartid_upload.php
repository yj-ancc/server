<?php
include 'login.php';
$login_information =  "/login.json";
$database_name = "ancc";

// Main function for collecting the informations
$post_data = file_get_contents("php://input");
// Decoding the json data to retrieve based on objects
$request = json_decode($post_data, true);
// customer object within the json is accessed in here
$smartid_doc_info = $request['smartid_doc_info'];
$smartid_doc_text = $request['smartid_doc_text'];

if(($con = get_connection_db($login_information, $database_name)) != NULL ) {


    $insert_prepare = $con->prepare("INSERT INTO smartid_doc_info (smart_id, id_category, id_type, front_page, back_page, error_info, options_completed, types, webcam_access_permission) VALUES (?,?,?,?,?,?,?,?,?)");
    $insert_prepare->bind_param("sssbbssss", $smart_id, $id_category, $id_type, $front_page, $back_page, $error_info, $options_completed, $types, $webcam_access_permission);


    $smart_id = $smartid_doc_info['smartid'];
    $id_type = $smartid_doc_info['id_type'];
    $id_category = $smartid_doc_info['id_category'];
    $front_page = base64_encode($smartid_doc_info['front_page']);
    //error_log($smartid_doc_info['front_page'], 3, '/tmp/session.log');
    $back_page = $smartid_doc_info['back_page'];
    $error_info = $smartid_doc_info['error_info'];
    $options_completed = $smartid_doc_info['options_completed'];
    $types = $smartid_doc_info['types'];
    $webcam_access_permission = $smartid_doc_info['webcam_access_permission'];
        /* Executing the mysql execution statement */
        if ($insert_prepare != '' && $insert_prepare->execute()) {
            echo "success";
            $insert_prepare->close();
        } else {
            echo "failed";
            if ($insert_prepare != '') $insert_prepare->close();
        } 


                //insert smartid_doc_text 

                for($i=0; $i<sizeof($smartid_doc_text['title']); $i++){

                    $insert_prepare = $con->prepare("INSERT INTO smartid_doc_text (smart_id, id_type, title, val) VALUES (?,?,?,?)");
                    $insert_prepare->bind_param("ssss", $smart_id, $id_type, $title, $val);
        
        
                    $smart_id = $smartid_doc_text['smartid'];
                    $id_type = $smartid_doc_text['id_type'];
                    $title = $smartid_doc_text['title'][$i];
                    $val = $smartid_doc_text['val'][$i];

                    error_log("test: ", 3, '/tmp/session.log');

                        if ($insert_prepare != '' && $insert_prepare->execute()) {
                            echo "success";
                            error_log("success", 3, '/tmp/session.log');
                            $insert_prepare->close();
                        } else {
                            echo "failed"; 
                            error_log("failed", 3, '/tmp/session.log');
                            if ($insert_prepare != '') $insert_prepare->close();
                        }
        
                }

    
} else {

}

return 0;



?>