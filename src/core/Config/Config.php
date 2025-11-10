<?php

namespace Core\Config;

use Exception;

class Config
{
    protected string $configDir;
    protected array $config = [];
    protected array $excludes = ['main.config.php'];

    public function __construct()
    {
        $this->configDir = dirname(__DIR__, 3) . '/config';
        $this->getConfigFromFiles();
    }

    public function all()
    {
        return $this->config;
    }

    public function database(string $variable = '')
    {
        $option = $_SERVER['SERVER_NAME'] == 'localhost' ? 'database_dev' : 'database_prod';

        return $this->get($option, $variable);
    }

    public function email(string $variable = '')
    {
        return $this->get('email', $variable);
    }

    public function system(string $variable = '')
    {
        return $this->get('system', $variable);
    }

    public function shop(string $variable = '')
    {

        return $this->get('shop', $variable);
    }

    protected function getConfigFromFiles()
    {
        $config_files_dir = array_diff(scandir($this->configDir), array('..', '.'));

        foreach($config_files_dir as $file_name) {
            if (in_array($file_name, $this->excludes)) {
                continue;
            }
            
            $file_config = require $this->configDir . '/' . $file_name;
            
            if (is_array($file_config)) {
                [$name, $data] = $file_config;
                $this->config[$name] = $data;
            }
        }
    }

    protected function get(string $config_name, string $variable = '')
    {
        if (! isset($this->config[$config_name])) {
            throw new Exception("{$config_name} config doesn't exist");
        }

        $config = $this->config[$config_name];

        if ($variable === '') {
            return $config;
        }

        if (! array_key_exists($variable, $config)) {
            throw new Exception("Variable {$variable} doesn't exist in {$config_name} config");
        }

        return $config[$variable];
    }
}