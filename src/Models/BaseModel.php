<?php

namespace Models;

use Core\Config\Config;
use Core\Database\DataBase;
use Core\TemplateEngine\TemplateEngine;
use Core\Utils\Helpers;

abstract class BaseModel
{
    public function __construct(
        protected Database $database,
        protected Helpers $helpers,
        protected TemplateEngine $templateEngine,
        protected Config $config
    ){}
}