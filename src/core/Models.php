<?php

namespace Core;

use Models\Newsletter;
use Models\Contact;

class Models
{
  protected ?DataBase $database = null;
  protected ?Newsletter $newsletter = null;
  protected ?Contact $contact = null;
  protected GlobalsContainer $globals_container;

  public function __construct(string $dsn, string $db_user, string $db_password, GlobalsContainer $globals_container) 
  {
    $this->database = new DataBase($dsn, $db_user, $db_password);
    $this->globals_container = $globals_container;
  }

  public function newsletter() 
  {
    if ($this->newsletter === null) {
      $this->newsletter = new Newsletter($this->database, $this->globals_container);
    }
    return $this->newsletter;
  }

  public function contact() 
  {
    if ($this->contact === null) {
      $this->contact = new Contact($this->database, $this->globals_container);
    }
    return $this->contact;
  }
}