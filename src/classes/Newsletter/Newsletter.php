<?php

namespace ClothesEcommerce\Newsletter;
use ClothesEcommerce\App\DataBase;

class Newsletter 
{
  protected $database;
  private $allowed_filter_columns = ['id', 'subscriber_name', 'email', 'is_active'];

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

  public function assignToken (string $subscriber_id, string $token_role_id) 
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

  public function getSubscriberByToken (string $token, string $token_role_id) 
  {
    $arguments['token'] = $token;
    $arguments['token_role_id'] = $token_role_id;
    $sql = 'SELECT
              newsletter_subscribers.id,
              newsletter_subscribers.subscriber_name,
              newsletter_subscribers.email,
              newsletter_subscribers.is_active,
              UNIX_TIMESTAMP(newsletter_tokens.created_at) AS token_timestamp
            FROM newsletter_subscribers
            INNER JOIN newsletter_tokens ON newsletter_subscribers.id = newsletter_tokens.subscriber_id
            WHERE 
              newsletter_tokens.token = :token AND
              newsletter_tokens.token_role_id = :token_role_id';
    return $this->database->SQL($sql, $arguments)->fetch();
  }

  public function activateSubscribtion (int $subscriber_id) 
  {
    $sql = 'UPDATE newsletter_subscribers 
            SET is_active = 1
            WHERE id = :subscriber_id';
    $this->database->SQL($sql, ['subscriber_id' => $subscriber_id]);
  }

  public function getSubscribersTable (string|null $keyword, string|null $order_by)
  {
    $arguments = [];
    $sql = 'SELECT *
            FROM newsletter_subscribers ';
    
    if ($keyword) {
      $arguments['keyword'] = '%' . $keyword . '%';
      $sql .= 'WHERE subscriber_name LIKE :keyword ';
    }

    if ($order_by && in_array($order_by, $this->allowed_filter_columns)) {
      $sql .= 'ORDER BY ' . $order_by;
    }
    $sql .= ';';

    return $this->database->SQL($sql, $arguments)->fetchAll();
  }

  public function getExportSubscribersData ()
  {
    $data['file_name'] = 'newsletter-subscribers';
    $data['headings'] = ['ID', 'SUBSCRIBER NAME', 'EMAIL', 'ACTIVITY STATUS'];
    $data['db_columns'] = ['id', 'subscriber_name', 'email', 'activity_status'];

    $sql = 'SELECT
              id,
              subscriber_name,
              email,
              is_active,
              CASE
                WHEN is_active = "1" THEN "Active"
                ELSE "Inactive"
              END AS activity_status
            FROM newsletter_subscribers
            ORDER BY id;';
    $data['db_data'] = $this->database->SQL($sql)->fetchAll();
    return $data;
  }
}