<?php

namespace ClothesEcommerce\Newsletter;
use ClothesEcommerce\App\DataBase;

class Newsletter 
{
  protected $database;

  public function __construct (DataBase $database) 
  {
    $this->database = $database;
  }

  public function addSubscriber (string $name, string $email) 
  {
    $arguments['subscriber_name'] = $name;
    $arguments['email'] = $email;
    $sql = 'INSERT INTO newsletter_subscribers (subscriber_name, email)
            VALUES (:subscriber_name, :email)';

    try {
      $this->database->SQL($sql, $arguments);
      return $this->database->lastInsertId();
    } catch (\PDOException $e) {
      if ($e->errorInfo[1] === 1062) {
        return false;
      }
      throw $e; 
    }
  }

  public function deleteSubscriber (int $id) 
  {
    $sql = 'DELETE FROM newsletter_subscribers
            WHERE id = :id';
    $this->database->SQL($sql, ['id' => $id]);
  }

  public function assignToken (string $subscriber_id, int $token_role_id) 
  {
    $loop = false;
    $arguments['subscriber_id'] = $subscriber_id;
    $arguments['token'] = generateToken();
    $arguments['token_role_id'] = $token_role_id;
    $sql = 'INSERT INTO newsletter_tokens (subscriber_id, token, token_role_id)
            VALUES (:subscriber_id, :token, :token_role_id)';

    do {
      try {
        $this->database->SQL($sql, $arguments);
        return $arguments['token'];
      } catch (\PDOException $e) {
        if ($e->errorInfo[1] === 1062) {
          $loop = true;
        }
      }
    } while ($loop);
  }

  public function getSubscriberByToken (string $token, int $token_role_id) 
  {
    $arguments['token'] = $token;
    $arguments['token_role_id'] = $token_role_id;
    $sql = 'SELECT * 
            FROM newsletter_subscribers
            WHERE id = (SELECT subscriber_id
                        FROM newsletter_tokens
                        WHERE token = :token AND token_role_id = :token_role_id)';
    return $this->database->SQL($sql, $arguments)->fetch();
  }

  public function activateSubscribtion (int $subscriber_id) 
  {
    $sql = 'UPDATE newsletter_subscribers 
            SET is_active = 1
            WHERE id = :subscriber_id';
    $this->database->SQL($sql, ['subscriber_id' => $subscriber_id]);
  }
}