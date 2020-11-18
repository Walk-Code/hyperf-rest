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
use App\Middleware\ValidateTokenMiddleware;
use Hyperf\HttpServer\Router\Router;

/**
 * version: v1
 * system routes
 */
#Router::addRoute(['GET', 'HEAD', 'OPTIONS'], '/menu/getList', 'App\Auth\Controller\MenuController@getList', ['middleware' => [CorsMiddleware::class]]);
Router::addGroup('/v1/system', function () {
    #Router::addRoute(['GET', 'HEAD', 'OPTIONS'], '/menu/getList', 'App\Auth\Controller\MenuController@getList', ['middleware' => [CorsMiddleware::class]]);
    #Router::addRoute(['GET', 'POST', 'HEAD', 'OPTIONS'], '/menu/tree', 'App\Auth\Controller\MenuController@toTree', ['middleware' => [CorsMiddleware::class]]);
    #Router::addRoute(['POST', 'OPTIONS'], '/menu/create', 'App\Auth\Controller\MenuController@create', ['middleware' => [CorsMiddleware::class]]);
    #Router::addRoute(['POST', 'OPTIONS'], '/menu/edit', 'App\Auth\Controller\MenuController@edit', ['middleware' => [CorsMiddleware::class]]);
    # Router::addRoute(['PUT', 'OPTIONS'], '/menu/update', 'App\Auth\Controller\MenuController@update',['middleware' => [CorsMiddleware::class]]);
    # Router::addRoute(['DELETE', 'OPTIONS'], '/menu/delete', 'App\Auth\Controller\MenuController@delete', ['middleware' => [CorsMiddleware::class]]);
    #Router::addRoute(['GET', 'HEAD', 'OPTIONS'], '/role/getList', 'App\Auth\Controller\RoleController@getList', ['middleware' => [CorsMiddleware::class]]);
    #Router::addRoute(['POST', 'HEAD', 'OPTIONS'], '/role/add', 'App\Auth\Controller\RoleController@create', ['middleware' => [CorsMiddleware::class]]);
    #Router::addRoute(['POST', 'HEAD', 'OPTIONS'], '/role/addMenu', 'App\Auth\Controller\RoleController@addMenus', ['middleware' => [CorsMiddleware::class]]);
    #Router::addRoute(['POST', 'HEAD', 'OPTIONS'], '/role/addAdminUser', 'App\Auth\Controller\RoleController@addAdminUsers', ['middleware' => [CorsMiddleware::class]]);
    #Router::addRoute(['DELETE', 'HEAD', 'OPTIONS'], '/role/delete', 'App\Auth\Controller\RoleController@delete', ['middleware' => [CorsMiddleware::class]]);
    #Router::addRoute(['PUT', 'HEAD', 'OPTIONS'], '/role/update', 'App\Auth\Controller\RoleController@update', ['middleware' => [CorsMiddleware::class]]);
    #Router::addRoute(['DELETE', 'HEAD', 'OPTIONS'], '/role/delete/menu', 'App\Auth\Controller\RoleController@deleteMenu', ['middleware' => [CorsMiddleware::class]]);
    Router::addRoute(['POST', 'HEAD', 'OPTIONS'], '/user/login', 'App\Auth\Controller\LoginController@postLogin', ['middleware' => [CorsMiddleware::class]]);
    Router::addRoute(['GET', 'HEAD', 'OPTIONS'], '/user/info', 'App\Auth\Controller\UserController@getUserInfo', ['middleware' => [CorsMiddleware::class, ValidateTokenMiddleware::class]]);
});


Router::addRoute(['GET', 'POST', 'HEAD', 'OPTIONS'], '/', 'App\V1\Controller\IndexController@index', ['middleware' => [CorsMiddleware::class]]);
Router::addRoute(['GET', 'POST', 'HEAD', 'OPTIONS'], '/upload', 'App\V1\Controller\IndexController@upload', ['middleware' => [CorsMiddleware::class]]);
