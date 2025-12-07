<?php

namespace Core\Utils;

use Core\Http\Request;
use Core\TemplateEngine\TemplateEngine;

class TemplateUrlPathManager
{
    protected array $urlParts = [];
    protected array $breadcrumbs = [];
    protected bool $isUrlModified = false;

    public function __construct(
        protected TemplateEngine $templateEngine,
        protected Request $request
    ){}

    public function saveData()
    {
        if (! $this->isUrlModified) {
            $this->makeUrlParts();
        }

        $this->makeBreadcrumbs();

        $this->saveDataToTemplateEngine();
    }

    public function removeUrlPart(string|int $partKey = 'last')
    {
        $this->makeUrlParts();


        if (is_string($partKey)) {
            if ($partKey === 'last') {
                array_pop($this->urlParts);

                $this->isUrlModified = true;
            }
        }

        if (isset($this->urlParts[$partKey])) {
            unset($this->urlParts[$partKey]);

            $this->isUrlModified = true;
        }
    }

    protected function makeUrlParts()
    {
        $this->urlParts = explode('/', trim($this->request->uri(), '/'));
    }

    protected function makeBreadcrumbs()
    {
        $url = '';

        foreach($this->urlParts as $part) {
            $url .= $part . '/';

            $this->breadcrumbs[] = [
                'url' => '/' . rtrim($url, '/'),
                'name' => ucwords(str_replace('-', ' ', $part))
            ];
        }
    }

    protected function saveDataToTemplateEngine()
    {
        $this->templateEngine->addGlobalVariable('url_parts', $this->urlParts);
        $this->templateEngine->addGlobalVariable('breadcrumbs', $this->breadcrumbs);
    }
}