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
return [
    \App\Auth\Service\MenuService::class => \App\Auth\Service\Impl\MenuServiceImpl::class,
    \App\Auth\Service\RoleService::class => \App\Auth\Service\Impl\RoleServiceImpl::class,
    \App\Auth\Service\LoginService::class => \App\Auth\Service\Impl\LoginServiceImpl::class,
];
