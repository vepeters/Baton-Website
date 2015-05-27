<?php

require_once "Mail.php";

$email_to = "info@batonmed.com";
$email_subject = "Contact request from Baton Website";

$host = "email-smtp.us-east-1.amazonaws.com";
$username = "";
$password = "";

function showresult($result) {
	// Get the original file
	$pagecontents = file_get_contents("index.html");
	// Substitute the result message
	$marker = "<!--contact-message-->";
	$result = $result . "<br/><br/>";
	$pagecontents = str_replace($marker, $marker . $result, $pagecontents);
	// Show the original plus the message
	echo $pagecontents;
	die();
}

function died($error) {
	$result = "We are very sorry, but there were error(s) found with the form you submitted. " .
			  "These errors appear below.<br /><br />" .
			  $error . "<br />" .
			  "Please go back and fix these errors.<br /><br />";
	showresult($result);
}

function clean_string($string) {
  $bad = array("content-type","bcc:","to:","cc:","href");
  return str_replace($bad,"",$string);
}

// Check for expected data
if(!isset($_POST['name']) ||
	!isset($_POST['email']) ||
	!isset($_POST['message'])) {
	died('We are sorry, but there appears to be a problem with the form you submitted.');	   
}

// Get expected data
$name = $_POST['name']; // required
$email_from = $_POST['email']; // required
$message = $_POST['message']; // required

// Validate expected data
$error_message = "";

$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
if(!preg_match($email_exp,$email_from)) {
	$error_message .= 'The Email Address you entered does not appear to be valid.<br />';
}

$string_exp = "/^[A-Za-z .'-]+$/";
if(!preg_match($string_exp,$name)) {
	$error_message .= 'The Name you entered does not appear to be valid.<br />';
}

if(strlen($message) < 2) {
	$error_message .= 'The message you entered does not appear to be valid.<br />';
}

// Display error message if there is one
if(strlen($error_message) > 0) {
	died($error_message);
}

// Set up message
$email_message = "Form details below.\n\n";

$email_message .= "Name: ".clean_string($name)."\n";
$email_message .= "Email: ".clean_string($email_from)."\n";
$email_message .= "Message: ".clean_string($message)."\n";

// Create email headers
$headers = array ('From' => $email_to, 'Reply-To' => $email_from, 'To' => $email_to, 'Subject' => $email_subject);

// Send the email
$smtp = Mail::factory('smtp',
  array ('host' => $host,
	'auth' => true,
	'username' => $username,
	'password' => $password));

$mail = $smtp->send($email_to, $headers, $email_message);

// Check for errors in the send
if (PEAR::isError($mail)) {
	died($mail->getMessage());
} else {
	// Indicate success
	showresult("Thank you for contacting Baton. We will respond to " . $email_from . " within 24 hours.");
}

?>