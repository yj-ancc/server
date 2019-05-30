<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

include_once 'login.php';
include_once 'names.php';
$login_information =  "/login.json";
$database_name = "ancc";
//live key:
//9dc0ac04c1e25a69a11bd12b04dcafafcae6d468fcb1ab0a57746fc544e4ef1f

//test key:
//828bc700f61718c2f5e7178ef4cf15722cf965b77653b9accd8adda1e5663f26

/*
example return result from rapid ID
{
    "VerifyDocumentResult": {
        "attributes": {
            "i:type": "VisaResponse"
        },
        "ActivityId": "7439295e-493f-4f65-9100-e21f97da2427",
        "OriginatingAgencyCode": "RNA0",
        "VerificationRequestNumber": "RO0000000067874",
        "VerificationResultCode": "Y"
    },
    "fieldDetails": {
        "BirthDate": "Full Match",
        "GivenName": "Full Match",
        "MiddleName": "Full Match",
        "FamilyName": "Full Match",
        "PassportNumber": "Full Match"
    },
    "pdfLink": "https://s3-ap-southeast-2.amazonaws.com/rapid-id-storage/pdfLambda/5cb3c09afc98da47644fdc63/RapidID_Report_20-05-2019_04:19:10.pdf",
    "rapidID": "VISA848"
}


*/

$mode = 'sandbox';//for testing
// $mode = 'live' // for live


$post_data = file_get_contents("php://input");

/* Decoding the json data to retrieve based on objects */
$request = json_decode($post_data, true);

//echo json_encode($request);


$reference_number = $request['reference_number'];
$document_link = $request['document_link'];
$document_type = $request['document_type'];
$submitted_date = $request['submitted_date'];
$doc_name = $request['doc_name'];
 

function curl($data, $url){
     
            $payload = json_encode($data);
            $request_headers = array(
                'token: 828bc700f61718c2f5e7178ef4cf15722cf965b77653b9accd8adda1e5663f26',
                'Content-Type: application/json',
            );
            
            // Prepare new cURL resource
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            // Set HTTP Header for POST request 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers );
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
            
            // Submit the POST request
            $result = curl_exec($ch);
            echo $result;
            // Close cURL session handle
            curl_close($ch);
            return $result;
}


function saveResponse($con, $result_new, $certificate_new, $field_details_new){
    $insert_prepare = $con->prepare("INSERT INTO rapid_id (reference_number,document_link, document_type, submitted_date, result, doc_name, certificate, field_details) VALUES (?,?,?,?,?,?,?,?)");
    $insert_prepare->bind_param("ssssssss", $reference_number, $document_link, $document_type , $submitted_date, $result,  $doc_name,   $certificate, $field_details);


    $reference_number = $GLOBALS['reference_number'];
    $document_link = $GLOBALS['document_link'];
    $document_type = $GLOBALS['document_type'];
    $submitted_date = $GLOBALS['submitted_date'];
    $result =  $result_new;
    $doc_name = $GLOBALS['doc_name'];;
    $certificate= $certificate_new;
    $field_details = $field_details_new;

    if ($insert_prepare != '' && $insert_prepare->execute()) {
        echo json_encode("insert rapid id table success");
        $insert_prepare->close();
    } else {
        echo json_encode("insert rapid id table failed");
        if ($insert_prepare != '') $insert_prepare->close();
        return -1;
    } 
}


function checkRefExist($con) {

        $sql = "SELECT `reference_number`, `result` FROM `rapid_id` where `reference_number` = '".$GLOBALS['reference_number']."' ";
        $result = $con->query($sql);


        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                //if reference num is already exist in database and the result is Y, no need to check it again
                if($row["result"]=='Y'){
                    return true;
                } else {
                    //delete the current record
                    $sql = "DELETE FROM rapid_id WHERE reference_number='".$GLOBALS['reference_number']."' ";

                    if ($con->query($sql) === TRUE) {
                        echo "Record deleted successfully";
                    } else {
                        echo "Error deleting record: " . $conn->error;
                    }
                    return false;
                }
            }
        } else {
                return false;
        }
        return false;

}


if(($con = get_connection_db($login_information, $database_name)) != NULL ) {

    //check if record already exist
    if(checkRefExist($con) === false){
        $response = null;
        switch ($request['type']) {
            case "AustralianBirthCertificate":
                $data = array(
                        "BirthDate" => $request['BirthDate'],
                        "GivenName" =>  $request['GivenName'],
                        "MiddleName" => $request['MiddleName'],
                        "FamilyName" => $request['FamilyName'],
                        "RegistrationState" =>  $request['RegistrationState'],
                        "RegistrationNumber" =>  $request['RegistrationNumber'],
                        "CertificateNumber" =>  $request['CertificateNumber']
                );
                $response = curl( $data, 'https://'.$mode.'.ridx.io/dvs/birthCertificate');
                
                break;
            case "AustralianDriverLicence":
                $data = array(
                    "BirthDate" => $request['BirthDate'],
                    "GivenName" =>  $request['GivenName'],
                    "MiddleName" => $request['MiddleName'],
                    "FamilyName" => $request['FamilyName'],
                    "LicenceNumber" => $request['LicenceNumber'],
                    "StateOfIssue" => $request['StateOfIssue']
                );
                 $response = curl( $data, 'https://'.$mode.'.ridx.io/dvs/driverLicence');
                break;
            case "Passport":
                $data = array(
                    "BirthDate" => $request['BirthDate'],
                    "GivenName" =>  $request['GivenName'],
                    "MiddleName" => $request['MiddleName'],
                    "FamilyName" => $request['FamilyName'],
                    "TravelDocumentNumber" => $request['TravelDocumentNumber'],
                    "Gender" => $request['Gender'],
                );
                 $response = curl( $data, 'https://'.$mode.'.ridx.io/dvs/passport');
                break;
            case "OverseaPassport":
                $data = array(
                    "BirthDate" => $request['BirthDate'],
                    "GivenName" =>  $request['GivenName'],
                    "MiddleName" => $request['MiddleName'],
                    "FamilyName" => $request['FamilyName'],
                    "PassportNumber" => $request['PassportNumber'],
                );
                 $response = curl( $data, 'https://'.$mode.'.ridx.io/dvs/visa');
                break;
            case "Immicard":
                $data = array(
                    "BirthDate" => $request['BirthDate'],
                    "GivenName" =>  $request['GivenName'],
                    "MiddleName" => $request['MiddleName'],
                    "FamilyName" => $request['FamilyName'],
                    "ImmiCardNumber" => $request['ImmiCardNumber'],
                );
                 $response = curl( $data, 'https://'.$mode.'.ridx.io/dvs/immiCard');
                break;
            default:
                echo "No ID type match";
        }

        // FIX this thing
        if($response){

                    $temp = json_decode($response, true);
                    $result = $temp['VerifyDocumentResult']['VerificationResultCode'];
                    $field_details = $temp['fieldDetails'];
                    $certificate = $temp['pdfLink'];


                    if(($con = get_connection_db($login_information, $database_name)) != NULL ) {
                        saveResponse( $con, $result, $field_details, $certificate);
                    }
        }

    }
    
}

/*

Type : 
AustralianBirthCertificate,     url : https://live.ridx.io/dvs/birthCertificate
AustralianDriverLicence,        url : https://live.ridx.io/dvs/driverLicence
Passport,                       url : https://live.ridx.io/dvs/passport
OverseaPassport,                url : https://live.ridx.io/dvs/visa
Immicard                        url : https://live.ridx.io/dvs/immiCard
*/






 
?>