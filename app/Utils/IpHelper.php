<?php

namespace App\Utils;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * ip helper
 * Class IpHelper
 */
class IpHelper {

    /**
     * @Inject()
     * @var RequestInterface
     */
    private $request;

    /**
     * get real IP
     * @return mixed
     */
    public static function getIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // 检查http client ip
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // 检查是否为代理ip
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}