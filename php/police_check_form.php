<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
use setasign\Fpdi\Fpdi;
require_once('fpdf/fpdf.php');
require_once('fpdi2/src/autoload.php');
include 'names.php';



/* Kick start the procedure to collect the broadcasted data from the frontend */
$post_data = file_get_contents("php://input");

/* Decoding the json data as it is encoded in JSON format */
$request = json_decode($post_data, true);


$reference_no = $request['ref_num'];
$given_name =  $request['given_name'];
$last_name = $request['last_name'];
$office_PO_Box = $request['office_PO_Box'];
$office_address = $request['office_address'];
$other_given_name = $request['other_given_name'];

$given_name_previous_one = $request['given_name_previous_one'];
$last_name_previous_one = $request['last_name_previous_one'];
$other_given_name_previous_one = $request['other_given_name_previous_one'];
$name_type_one = $request['name_type_one']; // Madiden, Alias or Previous


$given_name_previous_two = $request['given_name_previous_two'];
$last_name_previous_two = $request['last_name_previous_two'];
$other_given_name_previous_two = $request['other_given_name_previous_two'];
$name_type_two = $request['name_type_two']; // Madiden, Alias or Previous

$gender = $request['gender']; // Male, Female or Intersex

$birth_day = $request['birth_day'];
$birth_month = $request['birth_month'];
$birth_year = $request['birth_year'];

$birth_suburb = $request['birth_suburb'];
$birth_state = $request['birth_state'];
$birth_country = $request['birth_country'];



$address_1_street = $request['addresses'][0]['street'];
$address_1_suburb = $request['addresses'][0]['suburb'];
$address_1_state = $request['addresses'][0]['state'];
$address_1_postcode = $request['addresses'][0]['postcode'];
$address_1_country = $request['addresses'][0]['country'];
$address_1_date_from_day = $request['addresses'][0]['date_from_day'];
$address_1_date_from_month = $request['addresses'][0]['date_from_month'];
$address_1_date_from_year = $request['addresses'][0]['date_from_year'];



// extension of addresses
if($request['addresses'][4]){
$address_5_street = $request['addresses'][4]['street'];
$address_5_suburb = $request['addresses'][4]['suburb'];
$address_5_state = $request['addresses'][4]['state'];
$address_5_postcode =$request['addresses'][4]['postcode'];
$address_5_country = $request['addresses'][4]['country'];
$address_5_date_from_day = $request['addresses'][4]['date_from_day'];
$address_5_date_from_month = $request['addresses'][4]['date_from_month'];
$address_5_date_from_year = $request['addresses'][4]['date_from_year'];
$address_5_date_to_day = $request['addresses'][4]['date_to_day'];
$address_5_date_to_month = $request['addresses'][4]['date_to_month'];
$address_5_date_to_year = $request['addresses'][4]['date_to_year'];
}

$position_title = $request['position_title'];
$work_place = $request['work_place'];
$contact_with_vulnerable = $request['contact_with_vulnerable']; // No, without supervision, or with supervision



// initiate FPDI
$pdf = new Fpdi();
// add a page
$pdf->AddPage();
// set the source file
$pdf->setSourceFile('police_check_form_empty.pdf');
// import page 1


$tplIdx = $pdf->importPage(1);
// use the imported page and place it at position 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx, 0, 0, 200);


//write name
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(85, 52);
$pdf->Write(0, $given_name . " " . $last_name);

//write reference number
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(112, 57);
$pdf->Write(0, $reference_no);


//write office location P.O Box
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(255, 0, 0);
$pdf->SetXY(37, 207);
$pdf->Write(0, $office_PO_Box);

//write office locatio address
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(255, 0, 0);
$pdf->SetXY(37, 213);
$pdf->Write(0, $office_address);

$pdf->AddPage();  
$tplidx = $pdf->importPage(2, '/MediaBox');
$pdf->useTemplate($tplidx, 0, 0, 200); 


$pdf->AddPage();  
$tplidx = $pdf->importPage(3, '/MediaBox');
$pdf->useTemplate($tplidx, 0, 0, 200); 

//write name
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(85, 38);
$pdf->Write(0, $given_name . " " . $last_name);

//write reference number
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(112, 44);
$pdf->Write(0, $reference_no);




$pdf->AddPage();  
$tplidx = $pdf->importPage(4, '/MediaBox');
$pdf->useTemplate($tplidx, 0, 0, 200); 

$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(255, 0, 0);
$pdf->SetXY(105, 250);
$pdf->Write(0, $office_PO_Box);

$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(255, 0, 0);
$pdf->SetXY(105, 255);
$pdf->Write(0, $office_address);



$pdf->AddPage();  
$tplidx = $pdf->importPage(5, '/MediaBox');
$pdf->useTemplate($tplidx, 0, 0, 200); 

//Family Name
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(17, 235);
$pdf->Write(0, $last_name);


//given Name
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(17, 247);
$pdf->Write(0, $given_name);

//other given Name
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(17, 259);
$pdf->Write(0, $other_given_name);

//previous family Name
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(110, 23);
$pdf->Write(0, $last_name_previous_one);


//previous given Name
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(110, 35);
$pdf->Write(0, $given_name_previous_one);

//previous other given Name
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(110, 47);
$pdf->Write(0, $other_given_name_previous_one);


//---------name type

if ($name_type_one == 'Maiden') {
	//Maiden
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(141, 55);
	$pdf->Write(0, 'X');
} elseif ($name_type_one == 'Alias') {
	//Alias
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(161, 55);
	$pdf->Write(0, 'X');

} elseif ($name_type_one == 'Previous' || $name_type_one == 'Other' ){
	//Previous
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(183, 55);
	$pdf->Write(0, 'X');

}
//-------------- previous name second 

//previous family Name
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(110, 72);
$pdf->Write(0, $last_name_previous_two);


//previous given Name
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(110, 83);
$pdf->Write(0, $given_name_previous_two);

//previous other given Name
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(110, 96);
$pdf->Write(0, $other_given_name_previous_two);


//---------name type


if ($name_type_two == 'Maiden') {
//Maiden
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(141, 104);
$pdf->Write(0, 'X');
} elseif ($name_type_two == 'Alias') {
//Alias
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(161, 104);
$pdf->Write(0, 'X');

} elseif ($name_type_two == 'Previous'){
//previous
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(183, 104);
$pdf->Write(0, 'X');

}


if ($gender == 'Male') {
	//male
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(114, 170);
$pdf->Write(0, 'X');

} elseif ($gender == 'Female') {
	//female
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(114, 176);
$pdf->Write(0, 'X');


} elseif ($gender == 'Intersex'){
	//intersex
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(114, 182);
$pdf->Write(0, 'X');
}



//date of birth day
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(136, 207);
$pdf->Write(0, $birth_day);

//date of birth month
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(143, 207);
$pdf->Write(0, $birth_month);

//date of birth year
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(151, 207);
$pdf->Write(0, $birth_year);


//suburb
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(111, 223);
$pdf->Write(0, $birth_suburb);

//state
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(111, 237);
$pdf->Write(0, $birth_state);

//country
$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(111, 250);
$pdf->Write(0, $birth_country);


$pdf->AddPage();  
$tplidx = $pdf->importPage(6, '/MediaBox');
$pdf->useTemplate($tplidx, 0, 0, 200); 



//   ------------ address one --------------------
//street address
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(20, 59);
$pdf->Write(0, $address_1_street);


//suburb address
$pdf->SetXY(41, 65);
$pdf->Write(0, $address_1_suburb);

//state address
$pdf->SetXY(41, 70);
$pdf->Write(0, $address_1_state);

//postcode 
$pdf->SetXY(80, 70);
$pdf->Write(0, $address_1_postcode);


//country
$pdf->SetXY(41, 76);
$pdf->Write(0, $address_1_country);

//start date
$pdf->SetXY(39, 89);
$pdf->Write(0, $address_1_date_from_day.'    '.$address_1_date_from_month.'      '.$address_1_date_from_year);


//-------------------- address two -------------------

if($request['addresses'][1]) {
  $address_2_street = $request['addresses'][1]['street'];
  $address_2_suburb = $request['addresses'][1]['suburb'];
  $address_2_state = $request['addresses'][1]['state'];
  $address_2_postcode =$request['addresses'][1]['postcode'];
  $address_2_country = $request['addresses'][1]['country'];
  $address_2_date_from_day = $request['addresses'][1]['date_from_day'];
  $address_2_date_from_month = $request['addresses'][1]['date_from_month'];
  $address_2_date_from_year = $request['addresses'][1]['date_from_year'];
  $address_2_date_to_day = $request['addresses'][1]['date_to_day'];
  $address_2_date_to_month = $request['addresses'][1]['date_to_month'];
  $address_2_date_to_year = $request['addresses'][1]['date_to_year'];

  //street address
  $pdf->SetFont('Arial','B',8);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->SetXY(20, 110);
  $pdf->Write(0, $address_2_street);


  //suburb address
  $pdf->SetXY(41, 116);
  $pdf->Write(0, $address_2_suburb);

  //state address
  $pdf->SetXY(41, 121);
  $pdf->Write(0, $address_2_state);

  //postcode
  $pdf->SetXY(80, 121);
  $pdf->Write(0, $address_2_postcode);


  //country
  $pdf->SetXY(41, 127);
  $pdf->Write(0, $address_2_country);

  // from date
  $pdf->SetXY(39, 140);
  $pdf->Write(0, $address_2_date_from_day.'    '.$address_2_date_from_month.'      '.$address_2_date_from_year);

  // end date
  $pdf->SetXY(74, 140);
  $pdf->Write(0, $address_2_date_to_day.'    '.$address_2_date_to_month.'      '.$address_2_date_to_year);
}

// ---------------- address three -------------------------
//street address

if($request['addresses'][2]){
  $address_3_street = $request['addresses'][2]['street'];
  $address_3_suburb = $request['addresses'][2]['suburb'];
  $address_3_state = $request['addresses'][2]['state'];
  $address_3_postcode =$request['addresses'][2]['postcode'];
  $address_3_country = $request['addresses'][2]['country'];
  $address_3_date_from_day = $request['addresses'][2]['date_from_day'];
  $address_3_date_from_month = $request['addresses'][2]['date_from_month'];
  $address_3_date_from_year = $request['addresses'][2]['date_from_year'];
  $address_3_date_to_day = $request['addresses'][2]['date_to_day'];
  $address_3_date_to_month = $request['addresses'][2]['date_to_month'];
  $address_3_date_to_year = $request['addresses'][2]['date_to_year'];


  // street address
  $pdf->SetFont('Arial','B',8);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->SetXY(20, 161);
  $pdf->Write(0, $address_3_street);


  //suburb address
  $pdf->SetXY(41, 167);
  $pdf->Write(0, $address_3_suburb);

  //suburb address
  $pdf->SetXY(41, 172);
  $pdf->Write(0, $address_3_state);

  //postcode
  $pdf->SetXY(80, 172);
  $pdf->Write(0, $address_3_postcode);


  //country
  $pdf->SetXY(41, 178);
  $pdf->Write(0, $address_3_country);

  // from date
  $pdf->SetXY(39, 191);
  $pdf->Write(0, $address_3_date_from_day.'    '.$address_3_date_from_month.'      '.$address_3_date_from_year);

  // end date
  $pdf->SetXY(74, 191);
  $pdf->Write(0, $address_3_date_to_day.'    '.$address_3_date_to_month.'      '.$address_3_date_to_year);
}


// ---------------- address four -------------------------

if($request['addresses'][3]){
  $address_4_street = $request['addresses'][3]['street'];
  $address_4_suburb = $request['addresses'][3]['suburb'];
  $address_4_state = $request['addresses'][3]['state'];
  $address_4_postcode =$request['addresses'][3]['postcode'];
  $address_4_country = $request['addresses'][3]['country'];
  $address_4_date_from_day = $request['addresses'][3]['date_from_day'];
  $address_4_date_from_month = $request['addresses'][3]['date_from_month'];
  $address_4_date_from_year = $request['addresses'][3]['date_from_year'];
  $address_4_date_to_day = $request['addresses'][3]['date_to_day'];
  $address_4_date_to_month = $request['addresses'][3]['date_to_month'];
  $address_4_date_to_year = $request['addresses'][3]['date_to_year'];


  //street address
  $pdf->SetFont('Arial','B',8);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->SetXY(20, 212);
  $pdf->Write(0, $address_4_street);


  //suburb address
  $pdf->SetXY(41, 218);
  $pdf->Write(0, $address_4_suburb);

  //suburb address
  $pdf->SetXY(41, 223);
  $pdf->Write(0, $address_4_state);

  //postcode
  $pdf->SetXY(80, 223);
  $pdf->Write(0, $address_4_postcode);


  //country
  $pdf->SetXY(41, 229);
  $pdf->Write(0, $address_4_country);

  // from date
  $pdf->SetXY(39, 242);
  $pdf->Write(0, $address_4_date_from_day.'    '.$address_4_date_from_month.'      '.$address_4_date_from_year);

  // end date
  $pdf->SetXY(74, 242);
  $pdf->Write(0, $address_4_date_to_day.'    '.$address_4_date_to_month.'      '.$address_4_date_to_year);
}

//--------------- sectionB----------------------
// position title
$pdf->SetXY(112, 202);
$pdf->Write(0, $position_title);

// work place
$pdf->SetXY(112, 252);
$pdf->Write(0, $work_place);

$pdf->AddPage();  
$tplidx = $pdf->importPage(7, '/MediaBox');
$pdf->useTemplate($tplidx, 0, 0, 200); 


if ($contact_with_vulnerable == "No") {
    // No direct or indirect contact with children or vulnerable group
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(80.5, 77.5);
	$pdf->Write(0, 'X');
} elseif ($contact_with_vulnerable == "without supervision") {
    //Direct or indirect contact with children or vulnerable groups, without supervision
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(80.5, 88);
	$pdf->Write(0, 'X');
} elseif ($contact_with_vulnerable == "with supervision"){
    //Direct or indirect contact with children or vulnerable groups, with supervision
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(80.5, 99);
	$pdf->Write(0, 'X');
}


$pdf->AddPage();  
$tplidx = $pdf->importPage(8, '/MediaBox');
$pdf->useTemplate($tplidx, 0, 0, 200); 

//Family name
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(20, 54);
$pdf->Write(0, $last_name);

//given name
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(20, 65);
$pdf->Write(0, $given_name);

//other given name
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(20, 76);
$pdf->Write(0, $other_given_name);


//full name
$pdf->SetFont('Arial','B',8);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(110, 117);
$pdf->Write(0, $given_name.' '.$last_name);


$pdf->AddPage();  
$tplidx = $pdf->importPage(9, '/MediaBox');
$pdf->useTemplate($tplidx, 0, 0, 200); 

$pdf->AddPage();  
$tplidx = $pdf->importPage(10, '/MediaBox');
$pdf->useTemplate($tplidx, 0, 0, 200); 

$pdf->AddPage();  
$tplidx = $pdf->importPage(11, '/MediaBox');
$pdf->useTemplate($tplidx, 0, 0, 200); 

$pdf->AddPage();  
$tplidx = $pdf->importPage(12, '/MediaBox');
$pdf->useTemplate($tplidx, 0, 0, 200); 

$pdf->AddPage();  
$tplidx = $pdf->importPage(13, '/MediaBox');
$pdf->useTemplate($tplidx, 0, 0, 200); 

$invoice_folder = get_form_file_path(). '/' .$reference_no ;
$invoice_file_name = $invoice_folder.'/'.get_police_check_form_name();
$invoice_file_content = '';


if (mkdir($invoice_folder, 0777, true) ) {
    /* Procedure to download the invoice */
    /* Output the file name with 'F' tag */
    /* save it as invoice.pdf inside the reference number */
    $pdf->Output($invoice_file_name, 'F');
    // $pdf->Output($invoice_file_content,'S');
    echo json_encode($invoice_file_name);

} else if(file_exists($invoice_file_name))  {
    echo json_encode($invoice_file_name);
} else {
    /* If the necessary file is not created */
    echo json_encode('NC');
}

?>
