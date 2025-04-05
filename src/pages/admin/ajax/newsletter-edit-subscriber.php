<?php

use ClothesEcommerce\Validation\Validation;

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
  $id = trim($_POST['id']);
  $name = trim($_POST['name']);

  // validation
  $response = [];

  $name_error = Validation::length($name, 'Name', 2, 50, true);

  if (empty($id)) {
    $response['error'] = 'Id cannot be empty!';
    echo json_encode($response);
    exit();
  }
  
  if ($name_error) {
    $response['name'] = $name_error;
  }

  if (!empty($response)) {
    echo json_encode($response);
    exit();
  }
  
  // edit subscriber
  // $db_response = $app->newsletter()->addSubscriber($name, $email, $email_settings);

  // if ($db_response == '200') {
  //   createAdminMessageInSession (htmlspecialchars($name) . ' has been successfully added to the subscriber list.', 'success', $session);
  //   $response['success'] = true;
  //   $response['path'] = 'admin/newsletter';
  // }
  // else if ($db_response == '1062') {
  //   $response['error'] = 'This e-mail address has already been taken!';
  // }

  // echo json_encode($response);
  // exit();
}