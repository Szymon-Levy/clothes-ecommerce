<?php

$token = $_GET['token'] ?? null;

// If token not found
if (!$token) {
  redirect('404');
}

$subscriber = $app->newsletter()->getSubscriberByToken($token, 'ND');

// If subscriber object not returned
if (!isset($subscriber['id'])) {
  createUserMessageInSession('Invalid token. Try again.', 'error', $session);
  redirect('');
}

// Delete subscriber
$app->newsletter()->deleteSubscribers($subscriber['id']);
createUserMessageInSession('We’re reaching out to confirm that you have successfully unsubscribed from our newsletter. If this was a mistake or you change your mind, you can always re-subscribe.', 'info', $session);
redirect('');