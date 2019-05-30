<?php

include 'names.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

/*
 * Credits : PHPMailer Reference Code
 */
function email_sending($from_user, $from_pass, $to_address, $to_address_name, $reply_to, $cc='', $bcc='', $subj, $body, $path) {
  $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
  try {
    //Server settings
    // $mail->SMTPDebug = 2;
                               // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host =  'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $from_user;                 // SMTP username
    $mail->Password = $from_pass;                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 25;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom($from_user, 'ANCC');
    $mail->addAddress($to_address, $to_address_name);     // Add a recipient
    $mail->addReplyTo($reply_to, 'HelpTeam');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    if( $path != '') {
      $mail->addAttachment($path);         // Add attachments
    }
    //$mail->addAttachment('..', '..');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subj;
    $mail->Body    = $body;
    $mail->AddEmbeddedImage('../../assets/images/logo/ancc_logo.png', 'logo', 'logo.png');
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail_send = $mail->send();
    

    if($mail_send) {
      return 1;
    }
    else {
      return 0;
    }

  } catch (Exception $e) {
    return 0;
  }
}

$email_data = file_get_contents("php://input");

// Decoding the json data to retrieve based on objects
$request = json_decode($email_data, true);

/*
$debug = 0;
if( $debug)  {
$email = 'savy.1712@gmail.com';
$name = 'Sarveshwaran Rajarajan';
$body = 'Attaching invoice';
$subj = 'Invoice';
$ref_num = '5c3ffbafd76a1';
$type = 'invoice';
$data_path = get_data_path().'/'.$ref_num.'/'.$type.'.pdf';
} else  {
*/
$email = '';
$name = '';
$body ='';
$subj = '';
$data_path = '';


if( $request['type'] == 'invoice') {
  $ref_num = $request['ref_num'];
  $type  = $request['type'];
  $email = $request['email'];
  $name = $request['name'];
  $data_path = get_data_path().'/'.$ref_num.'/'.$type.'.pdf';
  $body = 'Attaching pdf from .. ';
  $subj = 'Ancc - Invoice';

} else {
  $customer_information = $request['cust'];
  $name = $customer_information['name'];
  $email = $customer_information['email'];
  $code = $customer_information['code'];
  // TODO : change the body according to the design
  //$body = "Your verification code is  <b>".$code."</b>";
  $body =  file_get_contents('../html/template_part1.html').$name.', <br><br> 

		
  The Verification Code to continue your background check application is  <b>'.$code.' 
  </b>Complete verification by entering this code, or by clicking on the website link
  below:
  http://www.ancc.com.au/exampleofalink
  You are only minutes away from lodging your application.
  Contact our friendly support staff during business hours if you require assistance.'

  .file_get_contents('../html/template_part2.html');

  $subj = 'Verification Code';
}


if(email_sending(from_mail(), from_pass(), $email, $name, reply_to_name(), '','',$subj , $body, $data_path) ) {
  echo json_encode('success');
} else {
    echo json_encode('failure');
}

?>
