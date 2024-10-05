<?php

namespace ClothesEcommerce\App;

class App
{
  protected $database = null;
  protected $product = null;
  protected $product_category = null;
  protected $user = null;

  public function __construct($dsn, $db_user, $db_password) 
  {
    $this->database = new DataBase($dsn, $db_user, $db_password);
  }

  // public function products() 
  // {
  //   if ($this->product === null) {
  //     $this->product = new Product($this->database);
  //   }
  //   return $this->product;
  // }
}