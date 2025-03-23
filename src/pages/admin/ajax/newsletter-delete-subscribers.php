<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  //csrf validation
  $csrf_error = isCsrfIncorrect($session);
  if ($csrf_error) {
    $response['error'] = $csrf_error;
    echo json_encode($response);
    exit();
  }

  // response definition
  $response = [];

  // post data
  $ids = isset($_POST['ids']) ? $_POST['ids'] :  '';
  $ids = json_decode($ids);

  if (!$ids) {
    $response['error'] = 'Incorrect data passed.';
    echo json_encode($response);
    exit();
  }

  $count = $app->newsletter()->deleteSubscribers($ids);

  if ($count > 0) {
    $response['count'] = $count;
    $subscriber = 'subscriber' . ($count > 1 ? 's' : '');
    $response['success'] = "$count $subscriber has been successfully removed from subscribers list.";
    echo json_encode($response);
    exit();
  }
  else {
    $response['info'] = "No subscriber with the given id was found.";
    echo json_encode($response);
    exit();
  }
}