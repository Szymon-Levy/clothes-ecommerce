<?php

namespace Core\Http\Response;

interface ResponseInterface
{
    public function send(): void;
}