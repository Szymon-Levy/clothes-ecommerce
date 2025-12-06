<?php

namespace Core\Utils\FlashMessage;

use Core\Http\Session;

abstract class AbstractFlashMessage
{
    const SUCCESS = 'success';
    const ERROR = 'error';
    const INFO = 'info';
    const WARNING = 'warning';

    protected string $content;
    protected string $status;
    protected string $type;

    public function __construct(
        protected Session $session
    ){}

    public function success(string $content): void
    {
        $this->set($content, self::SUCCESS);
    }

    public function error(string $content): void
    {
        $this->set($content, self::ERROR);
    }

    public function info(string $content): void
    {
        $this->set($content, self::INFO);
    }

    public function warning(string $content): void
    {
        $this->set($content, self::WARNING);
    }

    protected function set(string $content, string $status): void
    {
        $this->content = $content;
        $this->status = $status;

        $this->save();
    }

    protected function setType(string $type): void
    {
        $this->type = $type;
    }
    
    protected function save(): void
    {
        $this->session->flash(
            'flash_message', 
            [
                'type' => $this->type,
                'content' => $this->content, 
                'status' => $this->status
            ]
        );
    }
}