<?php

namespace Models;

use Core\Config\Config;
use Core\Database\DataBase;
use Core\TemplateEngine\TemplateEngine;
use Core\Utils\Utils;

abstract class BaseModel
{
    public function __construct(
        protected Database $database,
        protected Utils $utils,
        protected TemplateEngine $templateEngine,
        protected Config $config
    ){}
}