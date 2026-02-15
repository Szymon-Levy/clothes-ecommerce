<?php

namespace Core\TemplateEngine;

use Core\Http\Flash\Enums\FlashScope;
use Core\Http\Flash\FlashService;
use Core\Http\Request\Request;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TemplateEngineExtension extends AbstractExtension
{
    public function __construct(
        protected Request $request,
        protected FlashService $flashService
    ){}

    public function getFunctions()
    {
        return [
            new TwigFunction('assets', [$this, 'assets']),
            new TwigFunction('loadPageJs', [$this, 'loadPageJs'], ['is_safe' => ['html']]),
            new TwigFunction('honeypot', [$this, 'honeypot'], ['is_safe' => ['html']]),
            new TwigFunction('setActive', [$this, 'setActive']),
            new TwigFunction('getMessage', function(string $scope) {
                return $this->flashService->take(FlashScope::from($scope));
            })
        ];
    }

    public function assets(string $filePath): string
    {
        $filePath = ltrim($filePath, '/');

        if (file_exists($filePath)) {
            $filePath .= '?v=' . filemtime($filePath);
        }

        return '/' . $filePath;
    }

    public function loadPageJs(array|string $fileNames, string $source): ?string
    {
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
                return '<script src="' . $fullPath . '" defer type="module"></script>';
            }
        }

        return null;
    }

    public function honeypot(): string
    {
        return '
            <input 
                id="website" 
                type="text" 
                name="website" 
                autocomplete="new-password" 
                tabindex="-1"
                readonly
                style="position:absolute; left:-9999px; width:1px; height:1px;"
            />
        ';
    }

    public function setActive(string $pattern, string $className = 'active'): string
    {
        $routeName = $this->request->getAttribute('route_name');

        if (str_contains($pattern, '*')) {
            $prefix = rtrim($pattern, '*');

            if (str_starts_with($routeName, $prefix)) {
                return $className;
            }
        }

        if ($routeName === $pattern) {
            return $className;
        }

        return '';
    }
}