<?php

include '../src/bootstrap.php';

$data['var'] = 'jakiś string';
echo $twig->render('index.html', $data);