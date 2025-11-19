<?php 

namespace Core\Application;

use Controllers\ErrorsController;
use Core\Config\Config;
use Core\Container\Container;
use Core\Database\DataBase;
use Core\Http\Csrf;
use Core\Routing\Dispatcher;
use Core\Routing\Exceptions\MethodNotAllowedException;
use Core\Routing\Exceptions\RouteNotFoundException;

class Application
{
    protected Container $container;
    protected Dispatcher $dispatcher;
    protected static $instances = [];

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
            $route = $router->dispatch();
            $this->dispatcher->dispatch($route);
        } catch (RouteNotFoundException $e) {
            $this->handleError('404');
        } catch (MethodNotAllowedException $e) {
            $this->handleError('400');
        } catch (\Throwable $e) {
            $this->handleError('500', $e);
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

        // Dispatcher
        $this->dispatcher = $this->container->get(\Core\Routing\Dispatcher::class);

        // Csrf
        $this->container->get(Csrf::class)->setInCookie();
    }

    protected function __clone(){}

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    protected function handleError(string $code, ?\Throwable $e = null)
    {
        http_response_code($code);

        if ($code == '500') {
            error_log($e);
        }

        $handler = [ErrorsController::class, "error{$code}"];

        $this->dispatcher->dispatchHandler($handler, $e);
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