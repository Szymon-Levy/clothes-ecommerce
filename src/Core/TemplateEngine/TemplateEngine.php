<?php

namespace Core\TemplateEngine;

use Core\Config\Config;
use Core\Http\Session\Session;
use Twig\Environment;

class TemplateEngine
{
    protected Environment $engine;
    protected string $viewsDir;
    protected string $cacheDir;

    public function __construct(
        protected Config $config,
        protected Session $session,
        protected TemplateEngineExtension $templateEngineExtension
    )
    {
        $this->viewsDir = dirname(__DIR__, 2) . '/views';
        $this->cacheDir = dirname(__DIR__, 3). '/var/cache';
        
        $this->initEngine();
    }

    public function render(string $path, array $data = [])
    {
        return $this->engine()->render($path, $data);
    }

    public function addGlobalVariable(string $name, $value)
    {
        $this->engine()->addGlobal($name, $value);
    }

    protected function initEngine()
    {
        $twigLoader = new \Twig\Loader\FilesystemLoader($this->viewsDir);

        $twigSettings['cache'] = $this->cacheDir;
        $twigSettings['debug'] = $this->config->system('dev');
        $twigSettings['strict_variables'] = false;

        $twig = new Environment($twigLoader, $twigSettings);

        $twig->addGlobal('config', $this->config->all());
        $twig->addGlobal('session', $this->session->getTwigVariables());

        if ($this->config->system('dev') === true) {
            $twig->addExtension(new \Twig\Extension\DebugExtension());
        }

        $this->engine = $twig;

        $this->extendTwig();
    }

    protected function engine()
    {
        return $this->engine;
    }

    protected function extendTwig()
    {
        $this->engine->addExtension($this->templateEngineExtension);

        $this->engine->getExtension(\Twig\Extension\CoreExtension::class)->setDateFormat('d/m/Y H:i', '%d days');
    }
}