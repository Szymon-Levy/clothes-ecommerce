<?php

namespace ClothesEcommerce\App;
use ClothesEcommerce\Session\Session;
use ClothesEcommerce\Newsletter\Newsletter;
use ClothesEcommerce\Contact\Contact;

class App
{
  protected $database = null;
  protected $session = null;
  protected $newsletter = null;
  protected $contact = null;

  public function __construct($dsn, $db_user, $db_password) 
  {
    $this->database = new DataBase($dsn, $db_user, $db_password);
  }

  public function session() 
  {
    if ($this->session === null) {
      $this->session = new Session();
    }
    return $this->session;
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