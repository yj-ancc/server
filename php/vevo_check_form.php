<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
use setasign\Fpdi\Fpdi;
require_once('fpdf/fpdf.php');
require_once('fpdi2/src/autoload.php');



$reference_no = '123456';
$given_name = 'Jonathan';
$middle_name = 'middle Name';
$last_name = 'Jia';
$office_PO_Box = 'P.O Box';
$office_address = "office address";
$other_given_name = 'other given name';
$email = 'jonathanjy@outlook.com';
$mobile = '0426480870';

$birth_day = '27';
$birth_month = '10';
$birth_year = '1995';

$esignature = 'esignature';
$today = '27/02/2019';


// initiate FPDI
$pdf = new Fpdi();
// add a page
$pdf->AddPage();
// set the source file
$pdf->setSourceFile('vevo_check_form_empty.pdf');
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
$pdf->SetXY(112, 58);
$pdf->Write(0, $reference_no);

//write office location P.O Box
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(255, 0, 0);
$pdf->SetXY(37, 208);
$pdf->Write(0, $office_PO_Box);

//write office locatio address
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(255, 0, 0);
$pdf->SetXY(37, 214);
$pdf->Write(0, $office_address);


$pdf->AddPage();  
$tplidx = $pdf->importPage(2, '/MediaBox');
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

$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(16, 103);
$pdf->Write(0, $given_name);

$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(76, 103);
$pdf->Write(0, $middle_name);

$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(132, 103);
$pdf->Write(0, $last_name);

$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(20, 143);
$pdf->Write(0, $email);

$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(100, 143);
$pdf->Write(0, $mobile);



$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(20, 190);
$pdf->Write(0, $birth_day);

$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(80, 190);
$pdf->Write(0, $birth_month);


$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(140, 190);
$pdf->Write(0, $birth_year);



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
$pdf->SetXY(112, 45.5);
$pdf->Write(0, $reference_no);

$pdf->AddPage();  
$tplidx = $pdf->importPage(4, '/MediaBox');
$pdf->useTemplate($tplidx, 0, 0, 200); 


//write name
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(85, 38);
$pdf->Write(0, $given_name . " " . $last_name);

//write reference number
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(112, 45.5);
$pdf->Write(0, $reference_no);

//write name
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(20, 125);
$pdf->Write(0, $given_name );

//write name
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(80, 125);
$pdf->Write(0, $last_name );

//write name
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(20, 155);
$pdf->Write(0, $esignature );

//write name
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(80, 148);
$pdf->Write(0, $today );


$pdf->Output();

?>