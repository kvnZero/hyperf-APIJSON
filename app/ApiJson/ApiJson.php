<?php

namespace App\ApiJson;

use App\ApiJson\Parse\Parse;
use App\Constants\ResponseCode;
use Hyperf\HttpServer\Contract\RequestInterface;

class ApiJson
{
    public function __construct(protected RequestInterface $request, protected string $method)
    {
    }

    public function Query(): array
    {
        if (!is_array(json_decode($this->request->getBody()->getContents(), true))) {
            return [
                'code' => ResponseCode::SERVER_ERROR,
                'msg' => ResponseCode::getMessage(ResponseCode::SERVER_ERROR)
            ];
        }
        $parse = new Parse(json_decode($this->request->getBody()->getContents(), true), $this->method, $this->request->input('tag', ''));
        return array_merge([
            'code' => ResponseCode::SUCCESS,
            'msg' => ResponseCode::getMessage(ResponseCode::SUCCESS)
        ], $parse->handle());
    }
}