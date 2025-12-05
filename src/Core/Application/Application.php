<?php 

namespace Core\Application;

use Controllers\ErrorsController;
use Core\Config\Config;
use Core\Container\Container;
use Core\Database\DataBase;
use Core\Dispatcher\Dispatcher;
use Core\Http\Csrf;
use Core\Http\Response\AbstractResponse;
use Core\Http\Response\HtmlResponse;
use Core\Routing\Exceptions\MethodNotAllowedException;
use Core\Routing\Exceptions\RouteNotFoundException;

class Application
{
    protected Container $container;
    protected Dispatcher $dispatcher;
    protected ?AbstractResponse $response = null;
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
            $route = $router->matchRoute();
            $result = $this->dispatcher->dispatch($route);

            if (!$result instanceof AbstractResponse) {
                $this->response = $this->handleError(
                    '500', 
                    new \Exception('Invalid return type. AbstractResponse expected.')
                );
            } else {
                $this->response = $result;
            }
        } catch (RouteNotFoundException $e) {
            $this->response = $this->handleError('404');
        } catch (MethodNotAllowedException $e) {
            $this->response = $this->handleError('405');
        } catch (\Throwable $e) {
            $this->response = $this->handleError('500', $e);
        }

        if ($this->response instanceof AbstractResponse) {
            $this->response->send();
        } else {
            (new HtmlResponse('Internal Server error', 500))->send();
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
        $this->dispatcher = $this->container->get(Dispatcher::class);

        // Csrf
        $this->container->get(Csrf::class)->setInCookie();
    }

    protected function __clone(){}

    public function __wakeup() {throw new \Exception("Cannot unserialize a singleton.");}

    protected function handleError(string $code, ?\Throwable $e = null)
    {
        if ($code == '500') {
            error_log($e);
        }

        $handler = [ErrorsController::class, "error{$code}"];

        return $this->dispatcher->dispatchHandler($handler, ['e' => $e]);
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