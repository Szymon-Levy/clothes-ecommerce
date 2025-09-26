<?php

namespace Core;

class Helpers
{
  protected Session $session;

  public function __construct(Session $session)
  {
    $this->session = $session;
  }

  
}