<?php
include_once 'names.php';
include_once 'login.php';

header('Access-Control-Allow-Origin: '.get_server_det());
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

// Login credentials are present in the login.json file
$login_information =  "/".get_login_file();
// database named retrieved

$database_name = get_db_name();

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

$mode = 'sandbox'; //for testing
// $mode = 'live' // for live


$post_data = file_get_contents("php://input");

/* Decoding the json data to retrieve based on objects */
$request = json_decode($post_data, true);

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
  //echo $result;
  // Close cURL session handle
  curl_close($ch);
  return $result;
}


function saveResponse($con, $reference_number, $document_link, $document_type, $doc_name, $result_new, $field_details_new, $certificate_new){
    $insert_prepare = $con->prepare("INSERT INTO rapid_id (reference_number, document_link, document_type, submitted_date, result, doc_name, certificate, field_details) VALUES (?,?,?,?,?,?,?,?)");
    $insert_prepare->bind_param("ssssssss", $reference_number_t, $document_link_t, $document_type_t, $submitted_date_t, $result_t, $doc_name_t, $certificate_t, $field_details_t);
    $reference_number_t = $reference_number;
    $document_link_t = $document_link;
    $document_type_t = $document_type;
    $submitted_date_t = date("Y-m-d H:i:s");
    $result_t =  $result_new;
    $doc_name_t = $doc_name;
    $certificate_t = $certificate_new;
    $field_details_t = $field_details_new;

    if ($insert_prepare != '' && $insert_prepare->execute()) {
        // echo json_encode("success");
        $insert_prepare->close();
        return "success";
    } else {
        // echo json_encode("failed");
        if ($insert_prepare != '') $insert_prepare->close();
        return "failed";
    } 
}


function checkRefExist($con, $reference_number) {

        $sql = "SELECT reference_number, result FROM rapid_id where reference_number='".$reference_number."'";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                //if reference num is already exist in database and the result is Y, no need to check it again
                if ($row["result"]=='Y') {
                    return "success";
                } else {
                    //delete the current record
                    $sql = "DELETE FROM rapid_id WHERE reference_number='".$reference_number."'";

                    if ($con->query($sql) === TRUE) {
                        return "success";
                    } else {
                        return "cns";
                    }
                    return "failed";
                }
            }
        } else {
            return "failed";
        }
        return "failed";
}


/*
 * Establishing the DB connection
 *
 *
 */


if(($con = get_connection_db($login_information, $database_name)) != NULL ) {

    //check if record already exist
    if(checkRefExist($con, $reference_number) === "failed"){
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
                return "No ID match";
        }

        $temp = json_decode($response, true);
        $response_status = $temp['result']['statuscode'];
        // check for the response status.
        // response status would come in only for the case of not acceptable requests.
        if($response_status == '' || $response_status == NULL){
          $result = $temp['VerifyDocumentResult']['VerificationResultCode'];
          $field_details = json_encode($temp['fieldDetails']);
          $certificate = $temp['pdfLink'];
          if(($con = get_connection_db($login_information, $database_name)) != NULL) {
            echo json_encode(saveResponse( $con, $reference_number, $document_link, $document_type, $doc_name, $result, $field_details, $certificate));
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
