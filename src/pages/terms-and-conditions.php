<?php

$data['shop_name'] = SHOP_NAME;
$data['shop_address'] = SHOP_ADDRESS;
$data['shop_email'] = SHOP_EMAIL;
$data['shop_phone'] = SHOP_PHONE;
$data['domain'] = FIXED_DOMAIN;

$data['page_title'] = 'Terms and conditions';
echo $twig->render('terms-and-conditions.html', $data);