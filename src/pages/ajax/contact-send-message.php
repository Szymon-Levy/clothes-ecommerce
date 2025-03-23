<?php

use ClothesEcommerce\Validation\Validation;
use ClothesEcommerce\Email\Email;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  //csrf validation
  $csrf_error = isCsrfIncorrect($session);
  if ($csrf_error) {
    $response['error'] = $csrf_error;
    echo json_encode($response);
    exit();
  }

  // anti bot validation
  $bot_error = isFormFilledByBot();
  if ($bot_error) {
    $response['error'] = $bot_error;
    echo json_encode($response);
    exit();
  }

  // post data
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $subject = $_POST['subject'];
  $message = trim($_POST['message']);
  $policy = $_POST['policy'] ?? null;

  // validation
  $response = [];

  $name_error = Validation::length($name, 'Name', 2, 50, true);
  $email_error = Validation::email($email, true);
  $subject_allowed_values = [
    'Shipping & Delivery',
    'Returns & Exchanges',
    'Payment Issues',
    'Technical Support',
    'Account Management',
    'Other'
  ];
  $subject_error = Validation::multiValues($subject, 'Subject', $subject_allowed_values, true);
  $message_error = Validation::length($message, 'Message', 15, 200, true);
  
  if ($name_error) {
    $response['name'] = $name_error;
  }
  
  if ($email_error) {
    $response['email'] = $email_error;
  }
  
  if ($subject_error) {
    $response['subject'] = $subject_error;
  }
  
  if ($message_error) {
    $response['message'] = $message_error;
  }

  if (!$policy) {
    $response['policy'] = 'Accepting privacy policy is required!';
  }

  if (!empty($response)) {
    echo json_encode($response);
    exit();
  }

  //remove csrf session variable
  $session->removeSessionVariable('csrf');
  
  // save email data in database
  $app->contact()->saveMessage($name, $email, $subject, $message);

  //send copy of informations to sender
  $email_sender = new Email($email_settings);
  $email_data = [
    'name' => $name,
    'email' => $email,
    'subject' => $subject,
    'message' => replaceWhitespaces($message)
  ];
  
  $email_sender->sendEmail(
    $email_settings['admin_username'], 
    $email, 
    'Copy of message sent to ' . SHOP_NAME . ' administrator.', 
    'contact_message_copy', 
    $email_data
  );

  $response['success'] = 'Your message has been successfully sent to the administrator. We have sent a copy of your message to your email.';
  echo json_encode($response);
  exit();
}