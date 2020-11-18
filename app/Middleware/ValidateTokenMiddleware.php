<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\BusinessCode;
use App\Exception\Utils\AssertsHelper;
use App\Utils\JWTHepler;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidateTokenMiddleware implements MiddlewareInterface {
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var JWTHepler
     */
    private $jwtHelper;

    /**
     * @Inject
     * @var RequestInterface
     */
    private $request;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $jwt = $request->getHeader('access_token');
        AssertsHelper::notNull(isset($jwt[0]) , BusinessCode::getMessage(BusinessCode::TOKEN_IS_INVALID), BusinessCode::TOKEN_IS_INVALID);
        $jwt = $jwt[0];
        AssertsHelper::notNull($jwt, BusinessCode::getMessage(BusinessCode::TOKEN_IS_INVALID), BusinessCode::TOKEN_IS_INVALID);
        $decode = $this->jwtHelper::getInstance()->parseToken($jwt);
        AssertsHelper::notNull($decode['iss'], BusinessCode::getMessage(BusinessCode::TOKEN_IS_INVALID), BusinessCode::TOKEN_IS_INVALID);
        Context::set('userId', $decode['userId']);

        return $handler->handle($request);
    }
}