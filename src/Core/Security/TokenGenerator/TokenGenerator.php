<?php

namespace Core\Security\TokenGenerator;

class TokenGenerator
{
    public function generate(int $bytes = 32): string
    {
        return bin2hex(random_bytes($bytes));
    }
}