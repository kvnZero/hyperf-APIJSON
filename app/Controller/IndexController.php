<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\ApiJson\ApiJson;

class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function index(): array
    {
        return [
            "message" => 'Welcome use APIJSON-php by Hyperf'
        ];
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $apiJson = new ApiJson($this->request, 'GET');
        return $apiJson->Query();
    }

    /**
     * @return array
     */
    public function head(): array
    {
        $apiJson = new ApiJson($this->request, 'HEAD');
        return $apiJson->Query();
    }

    /**
     * @return array
     */
    public function post(): array
    {
        $apiJson = new ApiJson($this->request, 'POST');
        return $apiJson->Query();
    }

    /**
     * @return array
     */
    public function put(): array
    {
        $apiJson = new ApiJson($this->request, 'PUT');
        return $apiJson->Query();
    }

    /**
     * @return array
     */
    public function delete(): array
    {
        $apiJson = new ApiJson($this->request, 'DELETE');
        return $apiJson->Query();
    }
}
