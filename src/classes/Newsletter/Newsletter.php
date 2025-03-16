<?php

namespace ClothesEcommerce\Newsletter;
use ClothesEcommerce\App\DataBase;

class Newsletter 
{
  protected $database;
  private $allowed_filter_columns = ['id', 'subscriber_name', 'email', 'is_active', 'created_at'];
  private $results_count = 0;

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

  public function getSubscribersResultsCount ()
  {

    return $this->results_count;
  }

  public function getSubscribersTable (string $keyword, string $order_by, int $page, string $sort)
  {
    $arguments = [];
    $where_clauses = [];
    $from_source = 'FROM newsletter_subscribers';

    if ($keyword) {
        $arguments['keyword'] = '%' . $keyword . '%';
        $where_clauses[] = 'subscriber_name LIKE :keyword';
    }

    $built_where = $where_clauses ? ' WHERE ' . implode(' AND ', $where_clauses) : '';

    // Get count of results to pagination
    $sql = "SELECT COUNT(id) AS count $from_source $built_where;";
    $this->results_count = $this->database->SQL($sql, $arguments)->fetch()['count'];

    $order_clause = ' ORDER BY ';
    $order_clause .= ($order_by && in_array($order_by, $this->allowed_filter_columns)) ? "$order_by " : 'id ';
    $order_clause .= ($sort == 'd') ? 'DESC' : 'ASC';

    $limit_clause = sprintf(' LIMIT %d OFFSET %d', ADMIN_PAGINATION, ($page - 1) * ADMIN_PAGINATION);

    $sql = "SELECT 
      id,
      subscriber_name,
      email,
      is_active,
      created_at
    $from_source 
    $built_where 
    $order_clause 
    $limit_clause;";
    error_log(print_r($sql, TRUE));
    return $this->database->SQL($sql, $arguments)->fetchAll();
  }

  // public function getSubscribersTable (string|null $keyword, string|null $order_by, int $page)
  // {
  //   $arguments = [];
  //   $sql = 'SELECT *
  //           FROM newsletter_subscribers ';
  //   $sql_results_count = 'SELECT COUNT(id) AS count
  //           FROM newsletter_subscribers ';
    
  //   if ($keyword) {
  //     $arguments['keyword'] = '%' . $keyword . '%';
  //     $sql .= 'WHERE subscriber_name LIKE :keyword ';
  //     $sql_results_count .= 'WHERE subscriber_name LIKE :keyword ';
  //   }

  //   $this->results_count = $this->database->SQL($sql_results_count . ';', $arguments)->fetch()['count'];

  //   if ($order_by && in_array($order_by, $this->allowed_filter_columns)) {
  //     $sql .= 'ORDER BY ' . $order_by;
  //   }

  //   $sql .= 'LIMIT ' . ADMIN_PAGINATION . ' OFFSET ' . ($page -1) * ADMIN_PAGINATION;

  //   $sql .= ';';

  //   return $this->database->SQL($sql, $arguments)->fetchAll();
  // }

  public function getExportSubscribersData ()
  {
    $data['file_name'] = 'newsletter-subscribers';
    $data['headings'] = ['ID', 'SUBSCRIBER NAME', 'EMAIL', 'CREATED DATE', 'ACTIVITY STATUS'];
    $data['db_columns'] = ['id', 'subscriber_name', 'email', 'created_at', 'activity_status'];

    $sql = 'SELECT
              id,
              subscriber_name,
              email,
              created_at,
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