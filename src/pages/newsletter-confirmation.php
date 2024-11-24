<?php

use ClothesEcommerce\Email\Email;

$token = $_GET['token'] ?? null;

// If token not found
if (!$token) {
  redirect('404');
}

$subscriber = $app->newsletter()->getSubscriberByToken($token, 1);

// If subscriber object not returned
if (!isset($subscriber['id'])) {
  createUserMessageInSession('Invalid token. Try again.', 'error');
  redirect('');
}

// Try to activate if not active or do nothing with already activated subscribtion
if ($subscriber['is_active'] === 0) {
  // Activate subscriber
  $subscriber_id = $subscriber['id'];
  $app->newsletter()->activateSubscribtion($subscriber_id);

  // Send welcome email with deletion link
  $deletion_token = $app->newsletter()->assignToken($subscriber_id, 2);
  $email_sender = new Email($email_settings);
  $email_data = [
    'name' => $subscriber['subscriber_name'],
    'token' => $deletion_token
  ];
  
  $email_sender->sendEmail(
    $email_settings['admin_username'], 
    $subscriber['email'], 
    'Your newsletter subscribtion at ' . SHOP_NAME . ' is active.', 
    'newsletter_welcome', 
    $email_data
  );

  createUserMessageInSession('Your subscription has been activated. Please check your inbox for further information.', 'success');
}
else {
  createUserMessageInSession('Your subscription has already been activated.', 'info');
}

redirect('');