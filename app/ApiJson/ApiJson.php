<?php

namespace App\ApiJson;

use App\ApiJson\Parse\Parse;
use App\Constants\ResponseCode;
use App\Event\ApiJson\RequestHandleAfter;
use App\Event\ApiJson\RequestHandleBefore;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\ApplicationContext;
use Psr\EventDispatcher\EventDispatcherInterface;

class ApiJson
{
    public function __construct(protected RequestInterface $request, protected string $method)
    {
    }

    public function Query(): array
    {
        $content = $this->request->getBody()->getContents();
        $beforeEvent = new RequestHandleBefore($content, $this->method);
        ApplicationContext::getContainer()->get(EventDispatcherInterface::class)->dispatch($beforeEvent);

        if (empty($beforeEvent->response)) { //提供更多可能性, 如接入缓存功能
            if (!is_array(json_decode($beforeEvent->content, true))) {
                return [
                    'code' => ResponseCode::CODE_UNSUPPORTED_ENCODING,
                    'msg' => ResponseCode::getMessage(ResponseCode::CODE_UNSUPPORTED_ENCODING)
                ];
            }
            $parse = new Parse(json_decode($beforeEvent->content, true), $this->method, $this->request->input('tag', ''));
            $response = $parse->handle();
        } else {
            $response = $beforeEvent->response;
        }

        ApplicationContext::getContainer()->get(EventDispatcherInterface::class)->dispatch(new RequestHandleAfter($beforeEvent->content, $response));

        return array_merge([
            'code' => ResponseCode::CODE_SUCCESS,
            'msg' => ResponseCode::getMessage(ResponseCode::CODE_SUCCESS)
        ], $response);
    }
}