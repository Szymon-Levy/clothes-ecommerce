<?php

namespace Core\ValueObjects;

use InvalidArgumentException;

class Breadcrumbs
{
    protected array $breadcrumbsKeys = ['url', 'name'];

    public function __construct(
        protected array $breadcrumbs
    ){}

    public static function fromSegments(array $segments): self
    {
        $url = '';
        $breadcrumbs = [];

        foreach($segments as $segment) {
            $url .= $segment . '/';

            $breadcrumbs[] = [
                'url' => '/' . rtrim($url, '/'),
                'name' => ucwords(str_replace('-', ' ', $segment))
            ];
        }

        return new self($breadcrumbs);
    }

    public function editAtPosition(int $position, array $changes): self
    {
        $breadcrumbs = $this->breadcrumbs;

        if (!isset($breadcrumbs[$position])) {
            throw new InvalidArgumentException("Index {$position} doesn't exist");
        }

        foreach ($changes as $key => $value) {
            if (in_array($key, $this->breadcrumbsKeys, true)) {
                $breadcrumbs[$position][$key] = $value;
            }
        }

        return new self($breadcrumbs);
    }

    public function get(): array
    {
        return $this->breadcrumbs;
    }
}