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
  $policy = $_POST['policy'] ?? null;

  // validation
  $response = [];

  $name_error = Validation::length($name, 'Name', 2, 50, true);
  $email_error = Validation::email($email, true);
  
  if ($name_error) {
    $response['name'] = $name_error;
  }
  
  if ($email_error) {
    $response['email'] = $email_error;
  }

  if (!$policy) {
    $response['policy'] = 'Accepting privacy policy is required!';
  }

  if (!empty($response)) {
    echo json_encode($response);
    exit();
  }
  
  // try to add email
  $subscriber_id = $app->newsletter()->addSubscriber($name, $email);

  //email doesn't exist in db
  if (!$subscriber_id) {
    $response['error'] = 'This e-mail address is already subscribed to our newsletter!';
    echo json_encode($response);
    exit();
  }

  //assign token and send email to new subscriber
  $token = $app->newsletter()->assignToken($subscriber_id, 1);

  $email_sender = new Email($email_settings);
  $email_data = [
    'name' => $name,
    'token' => $token
  ];

  $email_sender->sendEmail(
    $email_settings['admin_username'], 
    $email, 
    'Welcome to ' . SHOP_NAME . ' - Confirm Your Newsletter Subscription', 
    'newsletter_subscribtion_confirmation', 
    $email_data
  );


  $response['success'] = 'We\'ve added you to our subscriber list. To confirm, please check your email and click the activation link.';
  echo json_encode($response);
  exit();
}