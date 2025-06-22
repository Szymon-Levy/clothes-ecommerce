<?php

$data['home_page'] = true;
$data['page_js'] = 'home';

echo $twig->render('front/index.html.twig', $data);