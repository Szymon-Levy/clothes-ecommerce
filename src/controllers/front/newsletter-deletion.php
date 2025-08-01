<?php

$token = trim($_GET['token'] ?? '');

// If token not found
if (!$token) {
  redirect('404');
}

$db_response = $app->newsletter()->deleteSubscribtion($token);

if ($db_response == '200') {
  createUserMessageInSession('We’re reaching out to confirm that you have successfully unsubscribed from our newsletter. If this was a mistake or you change your mind, you can always re-subscribe.', 'success', $session);
}
else if ($db_response == 'subscriber_not_found') {
  createUserMessageInSession('Invalid token. Try again.', 'error', $session);
}

redirect('');