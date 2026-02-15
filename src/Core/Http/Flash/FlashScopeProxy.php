<?php

namespace Core\Http\Flash;

use Core\Http\Flash\Enums\FlashScope;
use Core\Http\Flash\Enums\FlashType;

class FlashScopeProxy
{
    public function __construct(
        protected FlashService $flashService,
        protected FlashScope $scope
    ) {}

    public function success(string $message): void
    {
        $this->flashService->add($this->scope, FlashType::SUCCESS, $message);
    }

    public function error(string $message): void
    {
        $this->flashService->add($this->scope, FlashType::ERROR, $message);
    }

    public function info(string $message): void
    {
        $this->flashService->add($this->scope, FlashType::INFO, $message);
    }

    public function warning(string $message): void
    {
        $this->flashService->add($this->scope, FlashType::WARNING, $message);
    }
}