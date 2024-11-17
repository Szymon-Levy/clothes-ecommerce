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
  
  // // try to add email
  // $subscriber_id = $app->newsletter()->addSubscriber($name, $email);

  // //email doesn't exist in db
  // if (!$subscriber_id) {
  //   $response['error'] = 'This e-mail address is already subscribed to our newsletter!';
  //   echo json_encode($response);
  //   exit();
  // }

  // //assign token and send email to new subscriber
  // $token = $app->newsletter()->assignToken($subscriber_id, 1);

  // $emailObj = new Email($email_settings);
  // $email_data = [
  //   'name' => $name,
  //   'token' => $token
  // ];
  // $emailObj->sendEmail($email_settings['admin_username'], $email, 'Welcome to ' . SHOP_NAME . ' - Confirm Your Newsletter Subscription', 'newsletter_subscribtion_confirmation', $email_data);


  $response['success'] = 'Your message has been successfully sent to the administrator. We have sent a copy of your message to your email.';
  echo json_encode($response);
  exit();
}