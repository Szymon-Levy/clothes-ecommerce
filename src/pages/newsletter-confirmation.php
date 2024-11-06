<?php

$token = $_GET['token'] ?? null;

if (!$token) {
  // ================= tutaj wiadomość error =================
  redirect('404');
}

$is_successful = $app->newsletter()->confirmSubscribtion($token, 'activation');

if ($is_successful) {
  // echo 'Subskrybcja aktywowana';
}
else {
  // echo 'Błąd subskrybcji';
}

redirect('');