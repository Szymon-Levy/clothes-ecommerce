<?php

namespace Models;

use Models\BaseModel;
use Core\Email;

class Contact extends BaseModel
{
    private function saveMessage(string $name, string $email, string $subject, string $message)
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

        $email_sender = new Email($this->email_settings);

        $email_data = [
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message
        ];

        $email_body = $this->twig->render('email_templates/contact_message_copy.html.twig', $email_data);

        $send_email = $email_sender->sendEmail(
            $this->email_settings['admin_username'],
            $email,
            'Copy of message sent to ' . $this->global_vars['shop_info']['name'] . ' administrator.',
            $email_body
        );

        if ($send_email) {
            $this->database->commit();
            return '200';
        } else {
            $this->database->rollBack();
            return 'email_error';
        }
    }
}