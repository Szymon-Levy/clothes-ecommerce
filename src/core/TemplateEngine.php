<?php

namespace Core;

use Twig\Environment;

class TemplateEngine
{
    protected Environment $engine;

    public function __construct(
        protected Config $config,
        protected Session $session
    )
    {
        $twig_loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/views');

        $twig_settings['cache'] = dirname(__DIR__, 3). '/var/cache';
        $twig_settings['debug'] = $this->config->system('dev');
        $twig_settings['strict_variables'] = false;

        $twig = new \Twig\Environment($twig_loader, $twig_settings);

        $twig->addGlobal('config', $this->config->all());
        $twig->addGlobal('session', $this->session->getTwigVariables());

        if ($this->config->system('dev') === true) {
            $twig->addExtension(new \Twig\Extension\DebugExtension());
        }

        $this->engine = $twig;

        $this->extendTwig();
    }

    protected function extendTwig()
    {
        $assets = new \Twig\TwigFunction('assets', function (string $file_path) {
            if (file_exists($file_path)) {
                $file_path .= '?v=' . filemtime($file_path);
            }
    
            return $this->config->system('doc_root') . $file_path;
        });
    
        $this->engine->addFunction($assets);

        $loadPageJs = new \Twig\TwigFunction('loadPageJs', function (array|string $file_names, string $source) {
            if (is_string($file_names)) {
                $file_name = $file_names;
                $file_names = [];
                $file_names[] = $file_name;
            }

            foreach ($file_names as $file_name) {
                $file_path = 'js/' . $source . '/pages/' . $file_name . '.js';

                if (file_exists($file_path)) {
                    $file_path .= '?v=' . filemtime($file_path);
                    $full_path = $this->config->system('doc_root') . $file_path;
                    echo '<script src="' . $full_path . '" defer type="module"></script>';
                }
            }
        });

        $this->engine->addFunction($loadPageJs);

        $pageActiveStatus = new \Twig\TwigFunction('pageActiveStatus', function (string $current_page, string|null $url_part) {
            if ($current_page == $url_part) {
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
                        <input type="text" name="website" value="" autocomplete="off" tabindex="-1" />
                    </label>
                </div>
            ';
        });

        $this->engine->addFunction($honeypot);

        $this->engine->getExtension(\Twig\Extension\CoreExtension::class)->setDateFormat('d/m/Y H:i', '%d days');
    }

    public function engine()
    {
        return $this->engine;
    }
}