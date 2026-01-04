<?php

namespace Core\Utils;

class Helpers
{
    public function generateToken()
    {
        return bin2hex(random_bytes(16));
    }

    public function safeFilename(string $name, bool $withDate = true): string
    {
        $info = pathinfo($name);
        $ext  = isset($info['extension']) ? '.' . $info['extension'] : '';
        $name = $info['filename'];

        $cleanName = preg_replace('/[^a-z0-9]+/', '-', strtolower($name));
        
        $cleanName = trim(preg_replace('/-+/', '-', $cleanName), '-');

        return substr($cleanName, 0, 200) . ($withDate ? '_' . date('Y-m-d_H-i-s') : '') . $ext;
    }
}