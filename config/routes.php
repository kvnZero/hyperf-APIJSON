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
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD', 'PUT', 'DELETE'], '/', 'App\Controller\IndexController@index');

Router::addRoute(['POST'], '/get[/{tag}]', 'App\Controller\IndexController@get');
Router::addRoute(['POST'], '/head[/{tag}]', 'App\Controller\IndexController@head');
Router::addRoute(['POST'], '/post[/{tag}]', 'App\Controller\IndexController@post');
Router::addRoute(['POST'], '/put[/{tag}]', 'App\Controller\IndexController@put');
Router::addRoute(['POST'], '/delete[/{tag}]', 'App\Controller\IndexController@delete');

Router::get('/favicon.ico', function () {
    return '';
});
