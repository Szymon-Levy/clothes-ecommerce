<?php

namespace ClothesEcommerce\App;
use ClothesEcommerce\Newsletter\Newsletter;

class App
{
  protected $database = null;
  protected $newsletter = null;
  protected $session = null;

  public function __construct($dsn, $db_user, $db_password) 
  {
    $this->database = new DataBase($dsn, $db_user, $db_password);
  }

  public function newsletter() 
  {
    if ($this->newsletter === null) {
      $this->newsletter = new Newsletter($this->database);
    }
    return $this->newsletter;
  }

  public function session() 
  {
    if ($this->session === null) {
      $this->session = new Session();
    }
    return $this->session;
  }
}