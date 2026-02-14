<?php

namespace App\Models;

use Core\Config\Config;
use Core\Database\DataBase;
use Core\TemplateEngine\TemplateEngine;

abstract class BaseModel
{
    public function __construct(
        protected Database $database,
        protected TemplateEngine $templateEngine,
        protected Config $config
    ){}
}