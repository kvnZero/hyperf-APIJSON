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
                'code' => ResponseCode::CODE_UNSUPPORTED_ENCODING,
                'msg' => ResponseCode::getMessage(ResponseCode::CODE_UNSUPPORTED_ENCODING)
            ];
        }
        $parse = new Parse(json_decode($this->request->getBody()->getContents(), true), $this->method, $this->request->input('tag', ''));
        return array_merge([
            'code' => ResponseCode::CODE_SUCCESS,
            'msg' => ResponseCode::getMessage(ResponseCode::CODE_SUCCESS)
        ], $parse->handle());
    }
}