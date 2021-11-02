<?php

namespace App\ApiJson;

use App\ApiJson\Parse\Parse;
use App\Constants\ResponseCode;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Log\LogLevel;

class ApiJson
{
    /** @var array $request */
    protected $request;

    /** @var string $method */
    protected $method;

    public function __construct(RequestInterface $request, string $method)
    {
        $this->request = $request;
        $this->method = $method;
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