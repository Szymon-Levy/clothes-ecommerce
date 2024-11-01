<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = trim($_POST['email']);
  $policy = $_POST['policy'] ?? null;

  $errors = [];

  if (empty($email)) {
    $errors['email'] = 'Email cannot be empty!';
  }

  if (!$policy) {
    $errors['policy'] = 'Accepting privacy policy is required!';
  }

  echo json_encode($errors, JSON_FORCE_OBJECT);
  exit();
}