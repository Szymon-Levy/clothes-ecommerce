<?php

if ($_POST) {
  $name = $_POST['name'];
  $image = $_FILES['image']['name'];

  $name .= ' Lewandowski';

  $response = [
    'success' => true,
    'name'    => $name,
    'file'    => $image
  ];

  echo json_encode($response);
  exit();
}

$data['ds'] = 'dsadsa';
echo $twig->render('contact.html', $data);