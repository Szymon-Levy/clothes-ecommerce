<?php

namespace Models;

use Core\DataBase;
use Core\Utils;
use Core\TemplateEngine;
use Core\Config;

abstract class BaseModel
{
    public function __construct(
        protected DataBase $database,
        protected Utils $utils,
        protected TemplateEngine $template_engine,
        protected Config $config
    ){}
}