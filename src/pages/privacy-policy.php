<?php

$data['shop_name'] = SHOP_NAME;
$data['shop_address'] = SHOP_ADDRESS;
$data['shop_email'] = SHOP_EMAIL;
$data['shop_phone'] = SHOP_PHONE;
echo $twig->render('privacy-policy.html', $data);