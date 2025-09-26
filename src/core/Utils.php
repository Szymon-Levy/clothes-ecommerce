<?php

namespace Core;

class Utils
{
  protected Session $session;

  public function __construct(Session $session)
  {
    $this->session = $session;
  }

  
}