<?php
header('Access-Control-Allow-Origin: '.get_server_det());
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
/* Payment page using the information for the payment fees from this page */
/* Bankrupty to be charged for extra 30$ in addition to the Police check amount */
function add_extra_bankruptcy() {
  return '30.00';
}
/*Vevo amount to be charged for extra 15$ in addition to the Police check amount */
function add_extra_vevo() {
  return '15.00';
}
/* Postage charge amount for extra 4.5$ in addition to the Police check amount */
function add_extra_postage() {
  return '4.50';
}

function add_extra_police() {
  return '46.00';
}
/* Base price for the bankruptcy check amount */
function bankruptcy_amount() {
  return '30.00';
}
/* Base price for the vevo check amount */
function vevo_amount() {
  return '15.00';
}
/* Base price for the police check amount */
function police_check_amount() {
  return '46.00';
}

function get_gst_percentage(){
  return 0.10;
}

function volunteer_police_check_amount() {
  return '20.00';
}

function student_police_check_amount() {
  return '20.00';
}

?>
