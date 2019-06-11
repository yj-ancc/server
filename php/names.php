<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');



function debug_check() {
  return 0;
}

function from_mail() {
    return 'ancc.email@gmail.com';
}

function get_customer_previous_name() {
  return 'customer_previous_name';
}

function from_pass() {
    return 'ancc_123';
}

function email_name() {
    return 'ANCC';
}

function reply_to_name() {
    return 'nationalcharacterchecks@gmail.com';
}

function get_main_customer_table_name() {
    return 'customer_main';
}

function get_customer_main_address() {
    return 'customer_address';
}

function get_customer_postal_address() {
    return 'customer_postal_address';
}

function get_customer_previous_address() {
    return 'customer_previous_address';
}

function get_sec_customer_table_name() {
    return 'customer_secondary';
}

function name_of_server() {
    return 'localhost';
}

function get_payment_page_number() {
    return '3';
}

function get_login_file() {
    return 'login.json';
}

function get_db_name() {
    return 'ancc';
}

function get_customer_table_name() {
    return 'customer_info';
}

function get_invoice_val(){
    return 100;
}

function get_purpose_of_check() {
    return 'purpose_of_check';
}

function get_var_len(){
  return '255';
}

function get_payment_card_table_name() {
  return 'payment_card';
}

/* TODO : find the link from pdf to respective page */
function get_redirection_link_from_pdf() {
  return '';

}

function get_payment_paypal_table_name() {
  return 'payment_paypal';
}

function get_card_name() {
  return 'card';
}

function get_paypal_name() {
  return 'paypal';
}

function get_gpay_name() {
  return 'gpay';
}

function get_apay_name() {
  return 'apay';
}

function get_payment_table_name() {
  return 'payment';
}

function get_help_email() {
  return 'info@ancc.net.au';
}


function get_help_num() {
  return '1800 940 522';
}

function get_next_check() {
  return 'www.australiannationalcharactercheck.com.au';
}

function get_abn() {
  return '95 610 943 934';
}

function get_rfi_table() {
  return 'Flagg_and_rfi';
}

function get_application_date_table() {
  return 'application_dates';
}

function get_font_type() {
  return 'Arial';
}

function get_invoice_file_path() {
  return get_repo_path().'/data';
}

function get_invoice_name() {
  return 'invoice.pdf';
}

function get_form_file_path() {
  return get_repo_path().'/form';
}

function get_police_check_form_name() {
  return 'police_check_form.pdf';
}

function consent_table() {
  return 'customer_consent';
}

function abs_path() {
  return '/ancc/ancc_individual/ancc';
}

function get_repo_path() {
  $id = 0;
  if($id == 0) {
    // sarvesh dev env
    return '/home/sarveshwaran/'.abs_path();
  } else if ($id == 1) {
      // server config
      return '/home/ubuntu/';
  } else {
      return '';
  }
}

function get_server_det() {
  $id = 0;
  if ($id == 0) {
    // local setting
    return 'http://localhost/';
  } else if ($id == 1) {
    // server config
    return 'http://3.19.72.50/';
  }
}

function get_data_path() {
  return get_repo_path().'/data';
}

function get_form_path() {
  return get_repo_path().'/form';
}

function get_login_path() {
  return './../../../';
}


function get_stripe_key($path, $login_information) {
  if(file_exists($path.''.$login_information) == FALSE) {
      echo json_encode('NOT FOUND STRIPE KEY');
      return NULL;
  }
  $login_information = file_get_contents($path.''.$login_information);
  // echo json_encode($login_information);
  $request = json_decode($login_information, true);
  $key = $request['stripe']['privateKey'];
  return $key;
}


?>
