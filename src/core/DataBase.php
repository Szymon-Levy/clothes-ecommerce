<?php

namespace Core;

use PDO;

class DataBase extends PDO
{
  public function __construct(string $dsn, string $db_user, string $db_password) 
  {
    $settings[\PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
    $settings[\PDO::ATTR_EMULATE_PREPARES] = false;
    $settings[\PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    parent::__construct($dsn, $db_user, $db_password);
  }

  public function SQL(string $sql, array|null $arguments = null)
  {
    if (!$arguments) {
      return $this->query($sql);
    }
    $stmt = $this->prepare($sql);
    $stmt->execute($arguments);
    return $stmt;
  }
}