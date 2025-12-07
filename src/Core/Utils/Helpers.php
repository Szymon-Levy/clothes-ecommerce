<?php

namespace Core\Utils;

class Helpers
{
    public function generateToken()
    {
        return bin2hex(random_bytes(16));
    }
}