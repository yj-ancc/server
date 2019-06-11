<?php
include 'names.php';
include 'fpdf/fpdf.php';


header('Content-Type', 'application/json;charset=utf-8');
header('Access-Control-Allow-Origin: '.get_server_det());
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

// Main function for collecting the informations
$post_data = file_get_contents("php://input");
// Decoding the json data to retrieve based on objects
$request = json_decode($post_data, true);

$ref_num =  $_GET['ref_num'];
$file_path =  get_data_path().'/'.$ref_num. '/'. get_invoice_name();

// echo json_encode($file_path);
if(!file_exists($file_path)) {
  echo json_encode('NE');
} else {
    $file_name = basename($file_path);

    // ob_start();

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=\"$filename\"');
    // header('Content-Transfer-Encoding: binary');
    // header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    set_time_limit(0);
    header('Content-Length: ' . filesize($file_path)); //Absolute URL
    echo json_encode(readfile($file_path));

  }
?>
