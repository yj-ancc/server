<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

header('Content-Type', 'application/json');
include 'payment_charges.php';

$charges = police_check_amount().'&'.bankruptcy_amount().'&'.vevo_amount();
$charges .= '&'.add_extra_bankruptcy().'&'.add_extra_vevo().'&'.add_extra_postage().'&'.volunteer_police_check_amount();
$charges .= '&'.student_police_check_amount();

echo json_encode($charges);

?>
