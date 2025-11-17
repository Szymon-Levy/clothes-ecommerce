<?php

namespace Models;

use Models\BaseModel;
use Core\Utils\Email;

class ContactModel extends BaseModel
{
    protected function saveMessage(string $name, string $email, string $subject, string $message)
    {
        $arguments['sender_name'] = $name;
        $arguments['email'] = $email;
        $arguments['subject'] = $subject;
        $arguments['message'] = $message;

        $sql = '
            INSERT INTO contact_messages (sender_name, email, subject, message)
            VALUES (:sender_name, :email, :subject, :message)
        ';

        $this->database->SQL($sql, $arguments);
    }

    public function sendUserMessage(string $name, string $email, string $subject, string $message)
    {
        $this->database->beginTransaction();
        $this->saveMessage($name, $email, $subject, $message);

        $emailSender = new Email($this->config->email());

        $emailData = [
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message
        ];

        
        $emailBody = $this->templateEngine->render('email_templates/contact_message_copy.html.twig', $emailData);
        
        $sendEmail = $emailSender->sendEmail(
            $this->config->email('admin_username'),
            $email,
            'Copy of message sent to ' . $this->config->shop('name') . ' administrator.',
            $emailBody
        );

        if ($sendEmail) {
            $this->database->commit();
            return '200';
        } else {
            $this->database->rollBack();
            return 'email_error';
        }
    }
}