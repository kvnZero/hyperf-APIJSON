<?php

namespace App\ApiJson;

use App\ApiJson\Parse\Parse;
use App\Constants\ResponseCode;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Log\LogLevel;

class ApiJson
{
    public function __construct(protected RequestInterface $request, protected string $method)
    {
    }

    public function Query(): array
    {
        $parse = new Parse($this->request->getBody()->getContents(), $this->method, $this->request->input('tag', ''));
        return array_merge([
            'code' => ResponseCode::SUCCESS,
            'msg' => ResponseCode::getMessage(ResponseCode::SUCCESS)
        ], $parse->handle());
    }
}