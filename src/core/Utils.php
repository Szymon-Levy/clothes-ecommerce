<?php

namespace Core;

class Utils
{
    protected Session $session;
    protected array $global_vars;
    protected Csrf $csrf;

    public function __construct(GlobalsContainer $globals_container)
    {
        $this->session = $globals_container->get('session');
        $this->global_vars = $globals_container->get('global_vars');
        $this->csrf = $globals_container->get('csrf');
    }

    public function generateToken()
    {
        return bin2hex(random_bytes(16));
    }

    public function isCsrfIncorrect()
    {
        if (!isset($_POST['csrf']) || !$this->csrf->validateToken($_POST['csrf'])) {
            return 'Operation not allowed, refresh the page and try again!';
        }

        return false;
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