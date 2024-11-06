<?php

$token = $_GET['token'] ?? null;

if (!$token) {
  redirect('404');
}

$status = $app->newsletter()->getActivityStatusByToken($token);

if (!isset($status['is_active'])) {
  createUserMessageInSession('Nie ma takiego tokenu', 'error');
  redirect('');
}

if ($status['is_active'] === 0) {
  $subscriber_id = $status['id'];
  $app->newsletter()->activateSubscribtion($subscriber_id);
  $app->newsletter()->assignToken($subscriber_id, 'deletion');
  createUserMessageInSession('Twoja subskrybcja została aktywowana', 'success');
}
else {
  createUserMessageInSession('Już wcześniej aktywowano', 'info');
}

redirect('');