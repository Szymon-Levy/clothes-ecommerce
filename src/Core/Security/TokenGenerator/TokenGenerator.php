<?php

namespace Core\Security;

class TokenGenerator
{
    public function generate(int $bytes = 32): string
    {
        return bin2hex(random_bytes($bytes));
    }
}