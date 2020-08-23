<?php
namespace App\Exception\Utils;

class AssertsHelper {

    public static function isTrue($expression, $message) {
        if (!$expression) {
            ResponseHelper::fail(500, $message);
        }
    }

    public static function isNull($agrs, $message, $code = 422) {
        $arr = (array)$agrs;
        if (!empty($arr)) {
            ResponseHelper::fail($code, $message);
        }
    }

    public static function notNull($agrs, $message, $code = 422) {
        if (empty($agrs)) {
            ResponseHelper::fail($code, $message);
        }
    }
}
