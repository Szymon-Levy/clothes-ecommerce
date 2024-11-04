<?php

namespace ClothesEcommerce\Email;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email 
{
  protected $phpmailer;

  public function __construct (array $email_settings) 
  {
    $this->phpmailer = new PHPMailer(true);
    $this->phpmailer->isSMTP();
    if (IS_LOCAL) {
      $this->phpmailer->SMTPOptions = [
        'ssl' => [
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        ]
      ];
    }
    $this->phpmailer->SMTPAuth = true;
    $this->phpmailer->Host = $email_settings['server'];
    $this->phpmailer->SMTPSecure = $email_settings['security'];
    $this->phpmailer->Port = $email_settings['port'];
    $this->phpmailer->Username = $email_settings['username'];
    $this->phpmailer->Password = $email_settings['password'];
    $this->phpmailer->SMTPDebug = $email_settings['debug'];
    $this->phpmailer->CharSet = 'UTF-8';
    $this->phpmailer->isHTML(true);
  }
  
  public function sendEmail (string $from, string|array $to, string $subject, string $body)
  {
    $this->phpmailer->setFrom($from, 'Clothes Ecommerce');

    if (is_array($to)) {
      foreach ($to as $email) {
        $this->phpmailer->addAddress($email);
      }
    }
    else {
      $this->phpmailer->addAddress($to);
    }

    $this->phpmailer->Subject = $subject;
    $this->phpmailer->Body = '<!DOCTYPE html><html lang="en"><body>'. $body .'</body></html>';
    $this->phpmailer->AltBody = strip_tags($body);
    $this->phpmailer->send();
    return true;
  }
}