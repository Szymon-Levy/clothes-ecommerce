<?php

$data['wrapper_class'] = 'popup--video';
$data['video_name'] = $_POST['video_name'] ?? null;
$data['video_type'] = $_POST['video_type'] ?? null;

echo $twig->render('ui_elements/video_popup.html.twig', $data);