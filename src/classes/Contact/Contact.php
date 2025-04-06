<?php

namespace ClothesEcommerce\Contact;
use ClothesEcommerce\App\DataBase;
use ClothesEcommerce\Email\Email;

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

  public function sendUserMessage (string $name, string $email, string $subject, string $message, array $email_settings) 
  {
    $this->database->beginTransaction();
    $this->saveMessage($name, $email, $subject, $message);

    $email_sender = new Email($email_settings);
    $email_data = [
      'name' => $name,
      'email' => $email,
      'subject' => $subject,
      'message' => replaceWhitespaces($message)
    ];
    $send_email = $email_sender->sendEmail(
      $email_settings['admin_username'], 
      $email, 
      'Copy of message sent to ' . SHOP_NAME . ' administrator.', 
      'contact_message_copy', 
      $email_data
    );

    if ($send_email) {
      $this->database->commit();
      return '200';
    }
    else {
      $this->database->rollBack();
      return 'email_error';
    }
  }
}