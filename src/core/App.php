<?php

namespace App;
use ClothesEcommerce\Newsletter\Newsletter;
use ClothesEcommerce\Contact\Contact;

class App
{
  protected $database = null;
  protected $newsletter = null;
  protected $contact = null;

  public function __construct(string $dsn, string $db_user, string $db_password) 
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

  public function contact() 
  {
    if ($this->contact === null) {
      $this->contact = new Contact($this->database);
    }
    return $this->contact;
  }
}