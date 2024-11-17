<?php

use ClothesEcommerce\Validation\Validation;
use ClothesEcommerce\Email\Email;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // post data
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $subject = $_POST['subject'];
  $message = trim($_POST['message']);
  $policy = $_POST['policy'] ?? null;

  // // validation
  // $response = [];

  // $nameError = Validation::length($name, 'Name', 2, 50, true);
  // $emailError = Validation::email($email, true);
  
  // if ($nameError) {
  //   $response['name'] = $nameError;
  // }
  
  // if ($emailError) {
  //   $response['email'] = $emailError;
  // }

  // if (!$policy) {
  //   $response['policy'] = 'Accepting privacy policy is required!';
  // }

  // if (!empty($response)) {
  //   echo json_encode($response);
  //   exit();
  // }
  
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


  $response['success'] = 'We\'ve added you to our subscriber list. To confirm, please check your email and click the activation link.';
  echo json_encode($response);
  exit();
}