<?php

use App\Validation;

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
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');

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

  if (!empty($response)) {
    echo json_encode($response);
    exit();
  }
  
  // add subscriber
  $db_response = $app->newsletter()->addSubscriber($name, $email, $email_settings);

  if ($db_response == '200') {
    createAdminMessageInSession ($name . ' has been successfully added to the subscriber list.', 'success', $session);
    $response['success'] = true;
    $response['path'] = 'admin/newsletter';
  }
  else if ($db_response == '1062') {
    $response['error'] = 'This e-mail address has already been taken!';
  }
  else if ($db_response == 'email_error') {
    $response['error'] = 'A problem with sending the message to the specified email occured, check if the email address is correct and try again!';
  }

  echo json_encode($response);
  exit();
}