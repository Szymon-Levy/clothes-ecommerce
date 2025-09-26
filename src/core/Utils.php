<?php

namespace Core;

class Utils
{
  protected Session $session;

  public function __construct(Session $session)
  {
    $this->session = $session;
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
    if (!isset($_POST['website']) || !$_POST['website'] == '') {
        return 'You are not allowed to send this form!';
    }
    return false;
  }

  public function redirect(string $page)
  {
    header('Location: ' . DOC_ROOT . $page);
    exit;
  }

  public function replaceWhitespaces(string $text)
  {
    $new_text = str_replace(' ', '&nbsp;', $text);
    return nl2br($new_text);
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