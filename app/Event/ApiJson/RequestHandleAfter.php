<?php

namespace App\Event\ApiJson;

class RequestHandleAfter
{
    public function __construct(public string $content, public array $response)
    {
    }
}