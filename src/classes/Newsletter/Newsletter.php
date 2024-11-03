<?php

namespace ClothesEcommerce\Newsletter;
use ClothesEcommerce\App\DataBase;

class Newsletter 
{
  protected $database;

  public function __construct (DataBase $database) {
    $this->database = $database;
  }

  public function getText (bool $bool) {
    return $bool;
  }

  public function addSubscriber (string $name, string $email) {
    $arguments['subscriber_name'] = $name;
    $arguments['email'] = $email;
    $sql = 'INSERT INTO newsletter_subscribers (subscriber_name, email)
            VALUES (:subscriber_name, :email)';

    try {
      $this->database->SQL($sql, $arguments);
      return true;
    } catch (\PDOException $e) {
      if ($e->errorInfo[1] === 1062) {
        return false;
      }
      throw $e; 
    }
  }
}