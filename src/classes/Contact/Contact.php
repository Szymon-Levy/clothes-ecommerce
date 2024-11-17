<?php

namespace ClothesEcommerce\Contact;
use ClothesEcommerce\App\DataBase;

class Contact 
{
  protected $database;

  public function __construct (DataBase $database) 
  {
    $this->database = $database;
  }

  public function saveMessage (string $name, string $email, string $subject, string $message) 
  {
    $arguments['sender_name'] = $name;
    $arguments['email'] = $email;
    $arguments['subject'] = $subject;
    $arguments['message'] = $message;
    $sql = 'INSERT INTO contact_messages (sender_name, email, subject, message)
            VALUES (:sender_name, :email, :subject, :message)';
    $this->database->SQL($sql, $arguments);
  }
}