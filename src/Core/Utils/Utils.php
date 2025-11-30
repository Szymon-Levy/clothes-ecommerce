<?php

namespace Core\Utils;

use Core\Http\Session;

class Utils
{
    public function __construct(
        protected Session $session
    ){}

    public function generateToken()
    {
        return bin2hex(random_bytes(16));
    }

    public function isFormFilledByBot()
    {
        if (!isset($_POST['website']) || $_POST['website'] !== '') {
            return 'You are not allowed to send this form!';
        }

        return false;
    }

    public function showAdminMessage(string $content, string $type)
    {
        $this->session->flash('admin_message', ['content' => $content, 'type' => $type]);
    }

    public function showUserMessage(string $content, string $type)
    {
        $this->session->flash('user_message', ['content' => $content, 'type' => $type]);
    }
}