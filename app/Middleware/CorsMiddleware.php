<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\Config\Annotation\Value;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface {
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Value("cors.origin")
     * @var $origin
     */
    private $origin;

    /**
     * @Value("cors.headers")
     * @var $headers
     */
    private $headers;

    /**
     * @Value("cors.methods")
     * @var $methods
     */
    private $methods;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $response = Context::get(ResponseInterface::class);
        $response = $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Credentials', true)
            ->withHeader('Access-Control-Allow-Headers', $this->headers)
            ->withHeader('Access-Control-Allow-Methods', $this->methods)
            ->withHeader('Access-Control-Max-Age', '3600');

        if ($request->getMethod() == 'OPTIONS') {
            return $response;
        }

        return $handler->handle($request);
    }
}