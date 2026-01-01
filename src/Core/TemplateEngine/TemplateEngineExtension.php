<?php

namespace Core\TemplateEngine;

use Core\Http\Request;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TemplateEngineExtension extends AbstractExtension
{
    public function __construct(
        protected Request $request
    ){}

    public function getFunctions()
    {
        return [
            new TwigFunction('assets', [$this, 'assets']),
            new TwigFunction('loadPageJs', [$this, 'loadPageJs'], ['is_safe' => ['html']]),
            new TwigFunction('pageActiveStatus', [$this, 'pageActiveStatus']),
            new TwigFunction('honeypot', [$this, 'honeypot'], ['is_safe' => ['html']]),
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

    public function pageActiveStatus(string $currentPage, ?string $urlSegment): string
    {
        if ($currentPage == $urlSegment) {
            return 'active';
        }
        
        return '';
    }

    public function honeypot(): string
    {
        return '
            <div style="opacity: 0; position: absolute; top: 0; left: 0; height: 0; width: 0; z-index: -1;">
                <label>
                    leave this field blank to prove your humanity
                    <input type="text" name="website" value="" autocomplete="new-password" tabindex="-1" />
                </label>
            </div>
        ';
    }
}