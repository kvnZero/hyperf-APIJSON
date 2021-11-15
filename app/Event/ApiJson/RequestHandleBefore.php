<?php

namespace App\Event\ApiJson;

class RequestHandleBefore
{
    public array $response = []; //如果这里被赋值 则不会执行代码 而会直接抛出该结果

    public function __construct(public string $content, public string $method)
    {
    }
}