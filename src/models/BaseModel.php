<?php

namespace Models;

use Core\DataBase;
use Core\GlobalsContainer;
use Core\Utils;
use Twig\Environment;

abstract class BaseModel
{
    protected DataBase $database;
    protected Utils $utils;
    protected Environment $twig;
    protected array $global_vars;
    protected array $email_settings;

    public function __construct(DataBase $database, GlobalsContainer $globals_container)
    {
        $this->database = $database;
        $this->utils = $globals_container->get('utils');
        $this->twig = $globals_container->get('twig');
        $this->global_vars = $globals_container->get('global_vars');
        $this->email_settings = $globals_container->get('email_settings');
    }
}