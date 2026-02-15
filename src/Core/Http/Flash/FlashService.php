<?php

namespace Core\Http\Flash;

use Core\Http\Flash\Enums\FlashScope;
use Core\Http\Flash\Enums\FlashType;
use Core\Http\Session\Session;

class FlashService
{
    protected const SESSION_PREFIX = 'flash_';

    public function __construct(
        protected Session $session
    ) {}

    public function admin(): FlashScopeProxy
    {
        return new FlashScopeProxy($this, FlashScope::ADMIN);
    }

    public function front(): FlashScopeProxy
    {
        return new FlashScopeProxy($this, FlashScope::FRONT);
    }

    public function add(FlashScope $scope, FlashType $type, string $message): void
    {
        $key = self::SESSION_PREFIX . $scope->value . '_message';

        $this->session->set($key, [
            'type' => $type->value,
            'content' => $message
        ]);
    }

    public function take(FlashScope $scope): array
    {
        $key = self::SESSION_PREFIX . $scope->value . '_message';

        $message = $this->session->get($key, []);

        $this->session->remove($key);

        return $message;
    }
}