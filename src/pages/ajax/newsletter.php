<?php

use ClothesEcommerce\Validation\Validation;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // post data
  $email = trim($_POST['email']);
  $policy = $_POST['policy'] ?? null;

  // validation
  $response = [];

  $emailError = Validation::email($email, true);

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
  $response['success'] = 'Your email has been added!';
  echo json_encode($response);
  exit();
}