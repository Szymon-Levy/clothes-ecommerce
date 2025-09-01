<?php

namespace Models;

use Core\DataBase;

abstract class BaseModel
{
  protected DataBase $database;

  public function __construct(DataBase $database)
  {
    $this->database = $database;
  }
}