<?php
header('Content-Type', 'application/json');
include 'payment_charges.php';

$charges = police_check_amount().'&'.bankruptcy_amount().'&'.vevo_amount();
$charges .= '&'.add_extra_bankruptcy().'&'.add_extra_vevo().'&'.add_extra_postage().'&'.volunteer_police_check_amount();
$charges .= '&'.student_police_check_amount();

echo json_encode($charges);

?>
