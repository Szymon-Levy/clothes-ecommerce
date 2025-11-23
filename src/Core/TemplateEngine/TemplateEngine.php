<?php

namespace Core\TemplateEngine;

use Core\Config\Config;
use Core\Http\Session;
use Twig\Environment;

class TemplateEngine
{
    protected Environment $engine;
    protected string $viewsDir;
    protected string $cacheDir;

    public function __construct(
        protected Config $config,
        protected Session $session
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

        $twig = new \Twig\Environment($twigLoader, $twigSettings);

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
        $assets = new \Twig\TwigFunction('assets', function (string $filePath) {
            $filePath = ltrim($filePath, '/');

            if (file_exists($filePath)) {
                $filePath .= '?v=' . filemtime($filePath);
            }
    
            return '/' . $filePath;
        });
    
        $this->engine->addFunction($assets);

        $loadPageJs = new \Twig\TwigFunction('loadPageJs', function (array|string $fileNames, string $source) {
            if (is_string($fileNames)) {
                $fileName = $fileNames;
                $fileNames = [];
                $fileNames[] = $fileName;
            }

            foreach ($fileNames as $fileName) {
                $filePath = 'js/' . $source . '/pages/' . $fileName . '.js';

                if (file_exists($filePath)) {
                    $filePath .= '?v=' . filemtime($filePath);
                    $fullPath = '/' . $filePath;
                    echo '<script src="' . $fullPath . '" defer type="module"></script>';
                }
            }
        });

        $this->engine->addFunction($loadPageJs);

        $pageActiveStatus = new \Twig\TwigFunction('pageActiveStatus', function (string $currentPage, string|null $urlPart) {
            if ($currentPage == $urlPart) {
                return 'active';
            }
            
            return '';
        });

        $this->engine->addFunction($pageActiveStatus);

        $honeypot = new \Twig\TwigFunction('honeypot', function () {
            echo '
                <div style="opacity: 0; position: absolute; top: 0; left: 0; height: 0; width: 0; z-index: -1;">
                    <label>
                        leave this field blank to prove your humanity
                        <input type="text" name="website" value="" autocomplete="new-password" tabindex="-1" />
                    </label>
                </div>
            ';
        });

        $this->engine->addFunction($honeypot);

        $this->engine->getExtension(\Twig\Extension\CoreExtension::class)->setDateFormat('d/m/Y H:i', '%d days');
    }
}