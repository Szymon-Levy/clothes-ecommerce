<?php

$data['shop_name'] = SHOP_NAME;
$data['shop_address'] = SHOP_ADDRESS;
$data['shop_email'] = SHOP_EMAIL;
$data['shop_phone'] = SHOP_PHONE;

$data['page_title'] = 'Contact';
$data['page_js'] = 'contact';

echo $twig->render('front/contact.html.twig', $data);