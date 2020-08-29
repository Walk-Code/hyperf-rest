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

namespace App\Base\Controller;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractController extends AbstractRestController {
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * 获取客户端id
     * @return mixed
     */
    public function getIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) //to check ip passed from proxy
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * 获取header参数
     * 所有在header中自定义的参数 例如:自定义参数名:accessToken  那么 获取方法 $_SERVER['HTTP_ACCESSTOKEN']  所有均是大写
     * @param $headerKey
     * @return mixed|string
     */
    public function getHeader($headerKey) {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace(' ', '-', str_replace('_', ' ', strtolower(substr($key, 5))));
            // 设置header中key得值为小写
            $headers[$header] = $value;
        }
        $headerKey = strtolower($headerKey); // 转小写

        return isset($headers[$headerKey]) ? $headers[$headerKey] : '';
    }

    /**
     * 获取UA
     * @return mixed|string
     */
    public function getUserAgent() {
        return $this->getHeader('user-agent');
    }

    /**
     * 获取请求参数
     * @param $paramName
     * @return mixed|string|null
     */
    public function getParam($paramName) {
        $paramValue = "";
        if (isset($_GET[$paramName])) {
            $paramValue = $_GET[$paramName];
        } else if (isset($_POST[$paramName])) {
            $paramValue = $_POST[$paramName];
        }

        $requestPayLoad = $this->getRequestPayload();
        $contentType = $this->getHeader('Content-Type');
        if (strpos($contentType, 'application/json') !== false) {
            if (empty($requestPayLoad)) {
                return null;
            }

            $rawBody = json_decode($requestPayLoad, true);

            return isset($rawBody[$paramName]) ? $rawBody[$paramName] : null;
        }

        return $paramValue;
    }

    /**
     * 获取请求参数
     * @param $paramName
     * @param $defaultValue
     * @return string
     */
    public function getParamDefaultValue($paramName, $defaultValue) {
        $paramValue = $this->getParam($paramName);
        if (empty($paramValue)) {
            return $defaultValue;
        }

        return $paramValue;
    }

    /**
     * 通过流获取request body里面的内容
     * @return bool|false|string
     */
    public function getRequestPayload() {
        $rawInput = fopen('php://input', 'r');
        $tempStream = fopen('php://temp', 'r+');
        stream_copy_to_stream($rawInput, $tempStream);
        rewind($tempStream);

        return stream_get_contents($tempStream);
    }

    /**
     * 获取cookies
     * @param $paramName
     * @param $defaultValue
     */
    public function getCookies($paramName, $defaultValue = ''){
        return $this->request->cookie($paramName, $defaultValue);
    }
}
