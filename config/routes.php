<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use App\Middleware\CorsMiddleware;
use Hyperf\HttpServer\Router\Router;

/**
 * system routes
 */
Router::addGroup('/system', function (){
    Router::addRoute(['GET', 'POST', 'HEAD', 'OPTIONS'], '/menu', 'App\Auth\Controller\MenuController@getList',['middleware' => [CorsMiddleware::class]]);
    Router::addRoute(['GET', 'POST', 'HEAD', 'OPTIONS'], '/menu/tree', 'App\Auth\Controller\MenuController@toTree',['middleware' => [CorsMiddleware::class]]);
    Router::addRoute(['POST', 'OPTIONS'], '/menu/create', 'App\Auth\Controller\MenuController@create',['middleware' => [CorsMiddleware::class]]);
    Router::addRoute(['PUT', 'OPTIONS'], '/menu/update', 'App\Auth\Controller\MenuController@update',['middleware' => [CorsMiddleware::class]]);
    Router::addRoute(['DELETE', 'OPTIONS'], '/menu/delete', 'App\Auth\Controller\MenuController@delete', ['middleware' => [CorsMiddleware::class]]);
});



Router::addRoute(['GET', 'POST', 'HEAD', 'OPTIONS'], '/', 'App\V1\Controller\IndexController@index',['middleware' => [CorsMiddleware::class]]);
Router::addRoute(['GET', 'POST', 'HEAD', 'OPTIONS'], '/upload', 'App\V1\Controller\IndexController@upload',['middleware' => [CorsMiddleware::class]]);
