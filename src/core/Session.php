<?php

namespace Core;

class Session 
{
  public $user_message;
  public $admin_message;
  public $csrf;

  public function __construct ()
  {
    session_start();
    // set messages
    $this->user_message = $_SESSION['user_message'] ?? null;
    if ($this->user_message != null) unset($_SESSION['user_message']);

    $this->admin_message = $_SESSION['admin_message'] ?? null;
    if ($this->admin_message != null) unset($_SESSION['admin_message']);

    // set csrf token
    if (isset($_SESSION['csrf'])) {
      $this->csrf = $_SESSION['csrf'];
    }
    else {
      $this->csrf = generateCSRF();
      $this->setSessionVariable('csrf', $this->csrf);
    }
  }

  public function setSessionVariable (string $variableName, string|array $variableValue)
  {
    if (!property_exists($this, $variableName)) return false;
    $this->$variableName = $variableValue;

    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION[$variableName] = $variableValue;
  }

  public function removeSessionVariable (string $variableName)
  {
    if (!property_exists($this, $variableName)) return false;

    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    unset($_SESSION[$variableName]);
  }
}