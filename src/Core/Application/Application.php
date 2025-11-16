<?php 

namespace Core\Application;

use Core\Config\Config;
use Core\Container\Container;
use Core\Database\DataBase;
use Core\Http\Csrf;

class Application
{
    protected Container $container;
    private static $instances = [];

    protected function __construct()
    {
        $this->initApp();
    }

    public function run()
    {
        $router = $this->container->get(\Core\Routing\Router::class);

        $routes = require_once dirname(__DIR__, 2) . '/routes.php';
        $routes($router);

        try {
            $router->dispatch();
        } catch (\Throwable $e) {
            $router->dispatchError($e);
        }
    }

    protected function initApp()
    {
        // Container
        $this->container = new Container();

        // Database
        $this->container->set(DataBase::class, function($c) {
            $config = $c->get(Config::class)->database();
            
            extract($config);

            $dsn = "{$type}:host={$host};dbname={$dbName};port={$port};charset={$characterEncoding}";
            
            return new DataBase($dsn, $user, $password);
        });

        // Csrf
        $this->container->get(Csrf::class)->setInCookie();
    }

    protected function __clone(){}

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): Application
    {
        $class = static::class;

        if(!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }

        return self::$instances[$class];
    }
}