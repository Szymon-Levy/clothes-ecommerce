<?php

namespace Models;

use Core\DataBase;
use Core\GlobalsContainer;
use Core\Utils;

abstract class BaseModel
{
  protected DataBase $database;
  protected GlobalsContainer $globals_container;
  protected Utils $utils;

  public function __construct(DataBase $database, GlobalsContainer $globals_container)
  {
    $this->database = $database;
    $this->globals_container = $globals_container;
    $this->utils = $globals_container->get('utils');
  }
}