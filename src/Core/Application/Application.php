<?php 

namespace Core\Application;

use App\Controllers\ErrorsController;
use Core\Config\Config;
use Core\Container\Container;
use Core\Database\DataBase;
use Core\Dispatcher\Dispatcher;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\ResponseInterface;
use Core\Router\Exceptions\MethodNotAllowedException;
use Core\Router\Exceptions\RouteNotFoundException;
use Core\Router\Router;
use Core\Utils\Csrf;

class Application
{
    protected Container $container;
    protected Dispatcher $dispatcher;
    protected ?ResponseInterface $response = null;
    protected static $instances = [];

    protected function __construct()
    {
        $this->initApp();
    }

    protected function __clone(){}

    public function __wakeup() {throw new \Exception("Cannot unserialize a singleton.");}

    public static function getInstance(): Application
    {
        $class = static::class;

        if(!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }

        return self::$instances[$class];
    }

    public function run()
    {
        $router = $this->container->get(Router::class);

        $this->loadRoutes($router);

        try {
            $route = $router->matchRoute();
            $result = $this->dispatcher->dispatch($route);

            if (!$result instanceof ResponseInterface) {
                $this->response = $this->handleError(
                    '500', 
                    new \Exception('Invalid return type. ResponseInterface expected.')
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

        if ($this->response instanceof ResponseInterface) {
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

    protected function handleError(string $code, ?\Throwable $e = null)
    {
        if ($code == '500') {
            error_log($e);
        }

        $handler = [ErrorsController::class, "error{$code}"];

        return $this->dispatcher->dispatchHandler($handler, ['e' => $e]);
    }

    protected function loadRoutes(Router $router): void
    {
        $dir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'routes';

        $files = new \DirectoryIterator($dir);

        foreach ($files as $file) {
            if ($file->isDot() || $file->isDir() || $file->getExtension() !== 'php') {
                continue;
            }

            $routeRegistration = require $file->getRealPath();

            if (is_callable($routeRegistration)) {
                $routeRegistration($router);
            }
        }
    }
}