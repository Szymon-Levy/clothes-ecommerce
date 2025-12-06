<?php

namespace Core\Http;

class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function has(string $key)
    {
        return isset($_SESSION[$key]);
    }

    public function remove(string $key)
    {
        unset($_SESSION[$key]);
    }

    protected function clear()
    {
        session_destroy();
    }

    public function flash(string $key, $value = null)
    {
        if ($value === null) {
            $data = $this->get("flash_{$key}");
            $this->remove("flash_{$key}");

            return $data;
        }

        $this->set("flash_{$key}", $value);
    }

    public function getTwigVariables()
    {
        $data = [
            'flash_message' => $this->flash('flash_message')
        ];

        return $data;
    }
}