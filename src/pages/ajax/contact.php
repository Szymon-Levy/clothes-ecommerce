<?php

use ClothesEcommerce\Validation\Validation;
use ClothesEcommerce\Email\Email;
use ClothesEcommerce\Contact\Contact;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // post data
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $subject = $_POST['subject'];
  $message = trim($_POST['message']);
  $policy = $_POST['policy'] ?? null;

  // validation
  $response = [];

  $nameError = Validation::length($name, 'Name', 2, 50, true);
  $emailError = Validation::email($email, true);
  $subjectValues = [
    'Shipping & Delivery',
    'Returns & Exchanges',
    'Payment Issues',
    'Technical Support',
    'Account Management',
    'Other'
  ];
  $subjectError = Validation::multiValues($subject, 'Subject', $subjectValues, true);
  $messageError = Validation::length($message, 'Message', 15, 200, true);
  
  if ($nameError) {
    $response['name'] = $nameError;
  }
  
  if ($emailError) {
    $response['email'] = $emailError;
  }
  
  if ($subjectError) {
    $response['subject'] = $subjectError;
  }
  
  if ($messageError) {
    $response['message'] = $messageError;
  }

  if (!$policy) {
    $response['policy'] = 'Accepting privacy policy is required!';
  }

  if (!empty($response)) {
    echo json_encode($response);
    exit();
  }
  
  // save email data in database
  $app->contact()->saveMessage($name, $email, $subject, $message);

  //send copy of informations to sender
  $emailObj = new Email($email_settings);
  $email_data = [
    'name' => $name,
    'email' => $email,
    'subject' => $subject,
    'message' => replaceWhitespaces($message)
  ];
  $emailObj->sendEmail($email_settings['admin_username'], 
                       $email, 
                       'Copy of message sent to ' . SHOP_NAME . ' administrator.', 
                       'contact_message_copy', 
                       $email_data);

  $response['success'] = 'Your message has been successfully sent to the administrator. We have sent a copy of your message to your email.';
  echo json_encode($response);
  exit();
}