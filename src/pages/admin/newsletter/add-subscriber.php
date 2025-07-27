<?php

$data['page_title'] = 'Add Subscriber';
$data['page_js'] = 'newsletter';

echo $twig->render('admin/newsletter/add-subscriber.html.twig', $data);