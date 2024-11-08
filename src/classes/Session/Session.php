<?php

namespace ClothesEcommerce\Session;

class Session 
{
  public $message;

  public function __construct ()
  {
    session_start();
    $this->message = $_SESSION['message'] ?? null;
    if ($this->message != null) {
      unset($_SESSION['message']);
    }
  }
}