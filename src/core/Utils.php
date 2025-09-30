<?php

namespace Core;

class Utils
{
  protected Session $session;
  protected array $global_vars;

  public function __construct(Session $session, GlobalsContainer $globals_container)
  {
    $this->session = $session;
    $this->global_vars = $globals_container->get('global_vars');
  }

  public function generateToken() 
  {
    return bin2hex(random_bytes(16));
  }

  public function isCsrfIncorrect() 
  {
    if (!isset($_POST['csrf']) || $_POST['csrf'] != $this->session->csrf) {
      return 'Operation not allowed, refresh the page and try again!';
    }

    return false;
  }

  public function isFormFilledByBot() 
  {
    if (!isset($_POST['website']) || $_POST['website'] !== '') {
      return 'You are not allowed to send this form!';
    }

    return false;
  }

  public function redirect(string $page)
  {
    header('Location: ' . $this->global_vars['system']['doc_root'] . $page);
    exit;
  }

  public function createAdminMessageInSession(string $content, string $type) 
  {
    $this->session->setSessionVariable('admin_message', ['content' => $content, 'type' => $type]);
  }

  public function createUserMessageInSession(string $content, string $type) 
  {
    $this->session->setSessionVariable('user_message', ['content' => $content, 'type' => $type]);
  }
}