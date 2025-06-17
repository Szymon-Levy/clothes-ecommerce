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

$pagination_link_params = array_filter([
  'keyword' => $keyword ?: null,
  'orderby' => $order_by ?: null,
  'sort'    => $sort == 'a' ? null : 'd',
]);

$joined_pagination_params = [];

foreach ($pagination_link_params as $key => $value) {
  $joined_pagination_params[] = "$key=$value";
}

$data['pagination_string_params'] = implode('&', $joined_pagination_params);

echo $twig->render('admin/newsletter/index.html.twig', $data);