<?php
$data['page_title'] = 'Subscribers List';

$keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : null;
$order_by = isset($_GET['orderby']) ? htmlspecialchars($_GET['orderby']) : null;
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;

//error_log(print_r($order_by, TRUE));

$data['subscribers'] = $app->newsletter()->getSubscribersTable($keyword, $order_by, $page);
$data['keyword'] = $keyword;

echo $twig->render('admin/newsletter/index.html', $data);