<?php
//This file is required inside signup.php
//Therefore, this file only works when called from the signup.php file
//Many variables and $_POST values only exist from signup.php 


/**
 * In order to manually use PHPMailer (not with composer or anything else),
 * we simply have to use PHPMailer, Exception and SMTP while also require them.
 */

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load the required files
require(__DIR__ . '/../vendors/phpmailer/PHPMailer.php');
require(__DIR__ . '/../vendors/phpmailer/Exception.php');
require(__DIR__ . '/../vendors/phpmailer/SMTP.php');
require(__DIR__ . '/../../config/config.php'); //get email account credentials

/**
 * Everything above here is needed to make use of the PHPMailer in manual mode (without composer).
 */

$wholeWebPath = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$currentWebDirectory = substr($wholeWebPath, 0, strrpos($wholeWebPath, '/') + 1);
$activationFile = 'activate-account';

$sKeyPath = $currentWebDirectory . $activationFile . '?id=' . $userID . '&key=' . $activationKey . '&type=' . $sUserType; // since we use .htaccess in this folder, .php extension does not have to be specified

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 0;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $emailAcc;                     // SMTP username
    $mail->Password   = $emailPass;                               // SMTP password
    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom($emailAcc, 'Onos real estate');
    $mail->addAddress($_POST['email'], $_POST['name']);     // Add a recipient
    // $mail->addAddress('ellen@example.com');               // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Onos account activation';
    $mail->Body    = 'Welcome ' . $_POST['name'] . ',<br><br>
    in order to get started, please verify your account by clicking 
    <a href="' . $sKeyPath . '" style="color:blue;">
        on this link
    </a>.<br><br>
    Sincerely, your Onos Real Estate Team';
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    sendSuccessResponse((object) [
        "message" => "User successfully created and activation link sent"
    ]);
} catch (Exception $e) {
    sendErrorResponse('Activation mail error: ' . $mail->ErrorInfo);
}
