<?php

class Product 
{
  protected $database;

  public function __construct(DataBase $database)
  {
    $this->database = $database;
  }
}