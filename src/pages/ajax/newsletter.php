<?php

use ClothesEcommerce\Validation\Validation;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // post data
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $policy = $_POST['policy'] ?? null;

  // validation
  $response = [];

  $nameError = Validation::length($name, 'Name', 2, 50, true);
  $emailError = Validation::email($email, true);
  
  if ($nameError) {
    $response['name'] = $nameError;
  }
  
  if ($emailError) {
    $response['email'] = $emailError;
  }

  if (!$policy) {
    $response['policy'] = 'Accepting privacy policy is required!';
  }

  if (!empty($response)) {
    echo json_encode($response);
    exit();
  }
  
  // try to add email
  $add_subscriber = $app->newsletter()->addSubscriber($name, $email);

  //email doesn't exist in db
  if (!$add_subscriber) {
    $response['error'] = 'This e-mail address is already subscribed to our newsletter!';
    echo json_encode($response);
    exit();
  }

  //send email to new subscriber
  $response['success'] = 'We\'ve added you to our subscriber list. To confirm, please check your email and click the activation link.';
  echo json_encode($response);
  exit();
}