<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$application = Core\Application\Application::getInstance();

$application->run();