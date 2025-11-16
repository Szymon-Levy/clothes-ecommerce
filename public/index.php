<?php

use Core\Application\Application;

include '../src/bootstrap.php';

$application = Application::getInstance();
$application->run();