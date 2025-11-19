<?php

namespace Core\Config;

use Exception;

class Config
{
    protected string $configDir;
    protected array $config = [];
    protected array $excludes = [];

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
        $configFilesDir = array_diff(scandir($this->configDir), array('..', '.', 'settings'));

        foreach($configFilesDir as $fileName) {
            if (in_array($fileName, $this->excludes) || str_contains($fileName, 'example')) {
                continue;
            }
            
            $fileConfig = require $this->configDir . '/' . $fileName;
            
            if (is_array($fileConfig)) {
                [$name, $data] = $fileConfig;
                $this->config[$name] = $data;
            }
        }
    }

    protected function get(string $configName, string $variable = '')
    {
        if (! isset($this->config[$configName])) {
            throw new Exception("{$configName} config doesn't exist");
        }

        $config = $this->config[$configName];

        if ($variable === '') {
            return $config;
        }

        if (! array_key_exists($variable, $config)) {
            throw new Exception("Variable {$variable} doesn't exist in {$configName} config");
        }

        return $config[$variable];
    }
}