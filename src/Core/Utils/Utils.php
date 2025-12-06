<?php

namespace Core\Utils;


class Utils
{
    public function generateToken()
    {
        return bin2hex(random_bytes(16));
    }
}