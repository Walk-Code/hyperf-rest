<?php
namespace App\Exception\Utils;

use App\Exception\Core\BusinessException;

class ResponseHelper {
    /**
     * response fail message
     * @param int $code
     * @param $message
     */
    public static function fail($code, $message = '') {
        throw new BusinessException($code, $message);
    }

    /**
     * response success
     * @param $code
     * @param $message
     * @param array $data
     */
    public static function success($code, $message, $data = []){
        throw new BusinessException($code, $message, $data);
    }

}
