<?php

namespace Core;

use Exception;

class Config
{
    protected array $config = [];
    protected array $excludes = ['main.config.php'];

    public function __construct()
    {
        $dir = dirname(__DIR__, 2) . '/config';

        $config_filles_dir = array_diff(scandir($dir), array('..', '.'));

        foreach($config_filles_dir as $file_name) {
            if (in_array($file_name, $this->excludes)) {
                continue;
            }
            
            $file_config = require $dir . '/' . $file_name;
            
            if (is_array($file_config)) {
                [$name, $data] = $file_config;
                $this->config[$name] = $data;
            }
        }
    }

    public function all()
    {
        return $this->config;
    }

    public function database(string $variable = '')
    {
        $key = $_SERVER['SERVER_NAME'] == 'localhost' ? 'database_dev' : 'database_prod';

        if (! isset($this->config[$key])) {
            throw new Exception("Database config doesn't exist");
        }

        $config = $this->config[$key];

        if ($variable === '') {
            return $config;
        }

        if (! array_key_exists($variable, $config)) {
            throw new Exception("Variable {$variable} doesn't exist in database config");
        }

        return $config[$variable];
    }

    public function email(string $variable = '')
    {

        if (! isset($this->config['email'])) {
            throw new Exception("Email config doesn't exist");
        }

        $config = $this->config['email'];

        if ($variable === '') {
            return $config;
        }

        if (! array_key_exists($variable, $config)) {
            throw new Exception("Variable {$variable} doesn't exist in email config");
        }

        return $config[$variable];
    }

    public function system(string $variable = '')
    {

        if (! isset($this->config['system'])) {
            throw new Exception("System config doesn't exist");
        }

        $config = $this->config['system'];

        if ($variable === '') {
            return $config;
        }

        if (! array_key_exists($variable, $config)) {
            throw new Exception("Variable {$variable} doesn't exist in system config");
        }

        return $config[$variable];
    }

    public function shop(string $variable = '')
    {

        if (! isset($this->config['shop'])) {
            throw new Exception("Shop config doesn't exist");
        }

        $config = $this->config['shop'];

        if ($variable === '') {
            return $config;
        }

        if (! array_key_exists($variable, $config)) {
            throw new Exception("Variable {$variable} doesn't exist in shop config");
        }

        return $config[$variable];
    }
}