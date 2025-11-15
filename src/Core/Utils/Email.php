<?php

namespace Core\Utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email
{
    protected PHPMailer $phpMailer;

    public function __construct(array $emailSettings)
    {
        $this->phpMailer = new PHPMailer(true);
        $this->phpMailer->isSMTP();

        if ($_SERVER['SERVER_NAME'] == 'localhost') {
            $this->phpMailer->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];
        }

        $this->phpMailer->SMTPAuth = true;
        $this->phpMailer->Host = $emailSettings['server'];
        $this->phpMailer->SMTPSecure = $emailSettings['security'];
        $this->phpMailer->Port = $emailSettings['port'];
        $this->phpMailer->Username = $emailSettings['username'];
        $this->phpMailer->Password = $emailSettings['password'];
        $this->phpMailer->SMTPDebug = $emailSettings['debug'];
        $this->phpMailer->CharSet = 'UTF-8';
        $this->phpMailer->isHTML(true);
    }

    public function sendEmail(string $from, string|array $to, string $subject, string $body)
    {
        $this->phpMailer->setFrom($from, 'Clothes Ecommerce');
        
        if (is_array($to)) {
            foreach ($to as $email) {
                $this->phpMailer->addAddress($email);
            }
        } else {
            $this->phpMailer->addAddress($to);
        }

        $this->phpMailer->Subject = $subject;
        $this->phpMailer->Body = $body;
        
        try {
            $this->phpMailer->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}