<?php

$token = trim($_GET['token'] ?? '');

// If token not found
if (!$token) {
  redirect('404');
}

$db_response = $app->newsletter()->confirmSubscribtion($token, $email_settings);

if ($db_response == '200') {
  createUserMessageInSession('Your subscription has been activated. Please check your inbox for further information.', 'success', $session);
}
else if ($db_response == 'subscriber_not_found') {
  createUserMessageInSession('Invalid token. Try again.', 'error', $session);
}
else if ($db_response == 'already_confirmed') {
  createUserMessageInSession('Your subscription has already been activated.', 'info', $session);
}
else if ($db_response == 'token_expired') {
  createUserMessageInSession('Your activation token has expired and Your subscribtion has been deleted. Join us again and hurry up with the activation!', 'error', $session);
}
else if ($db_response == 'email_error') {
  createUserMessageInSession('A problem with sending the message to the specified email occured, check if the email address is correct and try again!', 'error', $session);
}

redirect('');