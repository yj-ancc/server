<?php

include "fpdf/fpdf.php";
include 'names.php';
include 'payment_charges.php';
include 'utils.php';

class PDF extends FPDF {

  function BasicTable($font_type, $header, $ref_num, $date, $invoice_num,
                    $name, $visa_details, $time,
                    $subtotal_section, $fee, $total_amount, $checks, $amount, $emp, $payment_method) {

    /* All the values present in this function are assumed based on trial and error functionality */
    /* These values are checked in various browsers and working perfectly  */
    /* Changing any value in this function will require subsequent change in other values */
    $blue_line_height = 0.93;

    $this->Image('fpdf/image/blueline.jpg', 0, $this->h    * $blue_line_height, 50);
    $this->Image('fpdf/image/blueline.jpg', 50, $this->h   * $blue_line_height, 50);
    $this->Image('fpdf/image/blueline.jpg', 100, $this->h  * $blue_line_height, 50);
    $this->Image('fpdf/image/blueline.jpg', 140, $this->h  * $blue_line_height, 50);
    $this->Image('fpdf/image/blueline.jpg', 180, $this->h  * $blue_line_height, 50);

    // top logo (ANCC logo)
    $this->Image('fpdf/image/ancc_logo.png',10,6,90);
    $this->SetFont($font_type, '', 12);

    // Ref No.
    $this->Cell(120);
    $this->Cell($this->w * 0.15, 7, '', 0, 1, 'R');
    $this->Cell(120);
    $this->SetFont($font_type, 'B', 11);
    $this->Cell($this->w * 0.22, 0, 'Ref No.', 0, 0, 'R');
    $this->Cell(1);
    $this->SetFont($font_type, '', 11);
    $this->Cell(3, 0 , $ref_num, 0, 1, 'L');
    $this->Ln(5);

    // Date
    $this->Cell(120);
    $this->SetFont($font_type, 'B', 11);
    $this->Cell($this->w * 0.22, 0, 'Date', 0, 0, 'R');
    $this->SetFont($font_type, '', 11);
    $this->Cell(1);
    $this->Cell(3 ,0, $date, 0, 1, 'L');
    $this->Ln(5);

    // Invoice No.
    $this->Cell(120);
    $this->SetFont($font_type, 'B', 11);
    $this->Cell($this->w * 0.22, 0, 'Invoice No.', 0, 0, 'R');
    $this->SetFont($font_type, '', 11);
    $this->Cell(1);
    $this->Cell(3 ,0, $invoice_num, 0, 1, 'L');


    $this->Ln(15);
    /* Tax invoice column*/
    $this->SetFont('Arial', '', 30);
    $this->SetTextColor(8,111,180);
    $this->Cell(1, 2, 'Tax Invoice','');
    $this->Ln(12);


    /* Billing Information */
    $this->SetFont($font_type, 'B', 10);
    $this->SetTextColor(0,0,0);
    $this->Cell(1, 2, 'Billing Information','');
    $this->Ln(8);
    $this->SetFont($font_type, '', 12);
    $this->Cell(1,1, $name,'');
    $this->Ln(1);
    // top table
    $this->Image('fpdf/image/pdfback.PNG',10,76,190);
    $this->SetFont($font_type, '', 13);

    /* Table details displaying the values */
    $this->Ln(15);
    $width_of_table = array($this->w * 0.45, $this->w * 0.20, $this->w * 0.25);
    $this->SetFont($font_type, 'B', 9);
    //
    // /* Column widths */
    $w = array($this->w * 0.48, $this->w * 0.17, $this->w * 0.25);
    //
    // /* Custom headers */
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],0,0,'M');
    $this->Ln();
    $this->SetFont($font_type, '', 12);
    $this->Ln(1);

    for($i = 0; $i < count($checks); $i++) {
      if($checks[$i] != 'Postage Request') {
        $this->Cell($w[0], 6, ' '.$checks[$i]. '-'. $emp,'', 0, '');
      } else {
          $this->Cell($w[0], 6, ' '.$checks[$i], '', 0, '');
      }
      $this->Cell($w[1], 6,  ' 1 x $'.$amount[$i], '', 0, '');
      $this->Cell($w[2], 6, '$'.$amount[$i], '', 0, '');
      $this->Ln();
    }

    // bottom table

    if($payment_method == get_paypal_name()) {
      $this->Ln(87);
      $this->Image('fpdf/image/pdfback2.jpg',10.2,194,189.4);

    } else {
        $this->Ln(87);
        $this->Image('fpdf/image/pdfback2.jpg',10.2,201,189.4);
        $this->Image('fpdf/image/pdfback3.jpg',10.2,194,189.58);

     }
    $gst = sprintf('%0.2f', $total_amount * get_gst_percentage());

    if(count($checks) == 4 ) {
      $this->Ln(0.3);
    } else if(count($checks) == 3) {
      $this->Ln(6.3);
    } else if (count($checks) == 2) {
        $this->Ln(12.3);
    } else if (count($checks) == 1) {
      $this->Ln(18.3);
    }
    $this->SetFont($font_type, 'B', 10);
    $this->Cell(100, 0, ' Payment details', 0, 0, 'L');
    $this->SetFont($font_type, '', 9);
    $this->Cell(35, 0, 'Subtotal' , 0, 0, 'L');

    $this->Cell(50 ,0, sprintf('$%0.2f', $total_amount - $gst), 0, 1, 'L');
    $this->Ln(7);
    $this->Cell($this->w * 0.13, 0, ' Payment Method:', 0, 0, 'L');
    $this->Cell(72.5,0, $visa_details, 0, 0, 'L');
    $this->Cell(35, 0, 'GST (10%)', 0, 0, 'L');
    $this->Cell($this->w * 0.08 ,0, '$'.$gst, 0, 1, 'L');
    $this->Ln(7);
    $this->Cell($this->w * 0.474, 0, ' Date: '.$date.' | Time: '.$time, 0, 0, 'L');

    /* if the paypal option is included as a part of the payment process */
    if($payment_method == get_paypal_name()) {
      $this->Ln(0);
      $this->Cell(100);
      $this->SetFont($font_type, 'B', 10);
      $this->Cell($this->w * 0.165, 0, 'Total Amount', 0, 0, 'L');
      $this->Cell($this->w * 0.08 ,0, '$'.$total_amount, 0, 1, 'L');
      $this->Ln(20);
    } else {
      $this->Cell($this->w * 0.168, 0, 'Card surcharge (1.5%)', 0, 0, 'L');
      $this->Cell($this->w * 0.08 ,0, '$'.$fee, 0, 1, 'L');
      $this->Ln(3);
      $this->Cell(100);
      $this->SetFont($font_type, 'B', 10);
      $this->Cell($this->w * 0.165, 7, 'Total Amount', 0, 0, 'L');
      $this->Cell($this->w * 0.08 ,7, '$'. ($total_amount + $fee), 0, 1, 'L');
      $this->Ln(10);
    }

    /* For your next police check text */
    $this->SetFont($font_type, '', 16);
    $this->SetTextColor(8,111,180);
    $this->Cell(0 , 5, 'For your next Police Check, visit', 0, 0, 'C');
    $this->Ln(1);
    $this->SetFont($font_type, 'B', 18);
    $this->Cell(0,20,get_next_check(), 0, 0, 'C',false, get_redirection_link_from_pdf());
    // $this->Image('fpdf/image/websitelinkimg.PNG', 30,240,150,0,'','http://google.com/');
    $this->Ln(10);

    $this->SetTextColor(0,0,0);
    $this->SetFont($font_type,'', 10);


    /* Footer information */
    $this->Image('fpdf/image/fot_logo_overlay.png', 5, $this->h * $blue_line_height - 11, 17);
    $this->Ln(26);
    $this->Cell(13);
    $this->SetFont('Arial', '', 9);
    $this->Ln(1);
    $this->Cell(12);
    $this->Cell(0 , 0, 'Australian National Character Check', 0, 1, 'L');
    $this->Ln(4);
    $this->Cell(12);
    $this->Cell(0 , 2, 'ABN : '.get_abn() , 0, 1, 'L');
    // $this->Ln(5);
    $this->Cell(13);


    $this->Image('fpdf/image/ph.png', $this->w * 0.66, $this->h * $blue_line_height - 2.5, 3);
    $this->Cell(118);
    $this->Cell(0,0, ' '.get_help_num(), 0, 1, 'L');

    $this->SetFont($font_type,'', 10);
    $this->Cell(109);

    $this->Image('fpdf/image/email.png', $this->w * 0.80, $this->h * $blue_line_height - 2.5, 5);
    $this->Cell(55);
    $this->Cell(30,0, ' '.get_help_email(), 0, 1, 'C');
    $this->SetFont($font_type,'', 10);
    $this->Cell(120);
  }
}


/* Kick start the procedure to collect the broadcasted data from the frontend */
$post_data = file_get_contents("php://input");

/* Decoding the json data as it is encoded in JSON format */
$request = json_decode($post_data, true);

$pdf = new PDF();
// Column headings
$debug = 0;

if( $debug ) {
  $ind_bus = 'i'; // $request['i_b'];
  $type_of_check = 'pbvh'; // $request['type'];
  $check_type_param = 'e';// $request['check_type_param'];
  $ref_num = '123';// $request['ref_num'];
  $date = '21/02/2021';// get_data_format($request['date']);
  $invoice_num = '100' ;// $request['invoice_num'];
  $name = 'Sar';//$request['name'];
  $last_four_digits = '4242';// $request['last_4'];
  $total_amount = '12.11';//$request['total_amount'];
  $visa_details = 'XXXX-'.$last_four_digits;
  $time = '12:01:02' ;// $request['time'];
  $payment_method = 'card';//$request['payment_type'];
  $fee = 0.76;//$request['fee'];
  $type_deal = 'visa' ;//$request['type_deal'];
} else  {
    $ind_bus =  $request['i_b'];
    $type_of_check = $request['type'];
    $check_type_param = $request['check_type_param'];
    $ref_num = $request['ref_num'];
    $date = get_data_format($request['date']);
    $invoice_num = $request['invoice_num'];
    $name = $request['name'];
    $total_amount = $request['total_amount'];
    $time = $request['time'];
    $payment_method = $request['payment_method'];
    $type_deal = $request['type_deal'];
}

if($payment_method == get_paypal_name()) {
  $visa_details = 'PayPal Checkout Express';
} else  {
  $fee = $request['fee'];
  $last_four_digits = $request['last_4'];
  $total_amount = $total_amount - $fee;
  $visa_details = 'XXXX-'.$last_four_digits;
}

/* Setting the font type to be arial */
$font_type = get_font_type();

$emp = '';
if( $check_type_param == 'e' ) {
  $emp = 'Employment';
} else if( $check_type_param == 's' ) {
    $emp = 'Student';
} else if ($check_type_param == 'v' ) {
    $emp = 'Volunteer';
}

$checks = array();
$relevant_amount = array();

for($i = 0; $i < strlen($type_of_check); $i++ ) {
  if( $type_of_check[$i] == 'p') {
    $checks[$i] = 'Police Check';
    if($check_type_param == 'e') {
      $relevant_amount[$i] = police_check_amount();
    } else if($check_type_param == 's') {
       $relevant_amount[$i] = student_police_check_amount();
    } else if($check_type_param == 'v') {
        $relevant_amount[$i] = volunteer_police_check_amount();
    }
  } else if( $type_of_check[$i] == 'b') {
      $checks[$i] = 'Bankruptcy Check';
      $relevant_amount[$i] = bankruptcy_amount();
  } else if ($type_of_check[$i] == 'v' ) {
      $checks[$i] = 'Vevo Check';
      $relevant_amount[$i] = vevo_amount();
  } else if ($type_of_check[$i] == 'h' ) {
      $checks[$i] = 'Postage Request';
      $relevant_amount[$i] = add_extra_postage();
  }
}

$data_header = array('   Description ', 'Price ', 'Amount (GST Incl.) ');
$subtotal_section = '';
/* card transaction : include the processing fee */
if( $payment_method == get_card_name()) {
    $subtotal_section = array( 'Subtotal', 'GST (10%) ', 'Credit card processing fee', 'Total Amount');
} /* paypal transaction */ else if ($payment_method == get_paypal_name()) {
    $subtotal_section = array( 'Subtotal', 'GST (10%)', 'Total Amount');
}

$pdf->SetFont($font_type,'',14);
$pdf->AddPage();
$pdf->BasicTable($font_type, $data_header, $ref_num, $date, $invoice_num, $name, $visa_details, $time, $subtotal_section, $fee, $total_amount, $checks, $relevant_amount, $emp, $payment_method);

$invoice_folder = get_invoice_file_path(). '/' .$ref_num ;
$invoice_file_name = $invoice_folder.'/'.get_invoice_name();
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
