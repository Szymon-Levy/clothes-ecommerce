<?php

// get data
$keyword = trim($_GET['keyword'] ?? '');
$order_by = trim($_GET['orderby'] ?? '');
$sort = trim($_GET['sort'] ?? 'a');
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;


// template data
$data['page_title'] = 'Subscribers List';
$data['subscribers'] = $app->newsletter()->getSubscribersTable($keyword, $order_by, $page, $sort);
$data['count'] = $app->newsletter()->getSubscribersResultsCount();
$data['keyword'] = $keyword;
$data['page'] = $page;
$data['order_by'] = $order_by;
$data['sort'] = ($sort == 'd') ? $sort : 'a';

echo $twig->render('admin/newsletter/index.html.twig', $data);