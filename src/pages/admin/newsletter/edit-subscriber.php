<?php

// get data
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
  createAdminMessageInSession('Wrong user id format given.', 'error', $session);
  redirect('admin/newsletter');
  exit;
}

$subscriber = $app->newsletter()->getSubscriberById($id);

if (!$subscriber) {
  createAdminMessageInSession('User with given id doesn\'t exists.', 'error', $session);
  redirect('admin/newsletter');
  exit;
}

//template data
$data['subscriber'] = $subscriber;
$data['page_title'] = 'Edit Subscriber';

echo $twig->render('admin/newsletter/edit-subscriber.html.twig', $data);