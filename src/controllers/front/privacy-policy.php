<?php

$data['shop_name'] = SHOP_NAME;
$data['shop_address'] = SHOP_ADDRESS;
$data['shop_email'] = SHOP_EMAIL;
$data['shop_phone'] = SHOP_PHONE;

$data['page_title'] = 'Privacy policy';

echo $twig->render('front/privacy-policy.html.twig', $data);