<?php
namespace App\Exception\Core;


class BusinessException extends ApplicationException {
    private $data = [];

    public function __construct() {
        $args = func_num_args(); // 获取多个参数
        if ($args == 1) {
            $value = func_get_arg(0);
            // 如果为错误信息则直接抛出
            if (is_string($value)) {
                parent::outputCustomMessagException($value);
            } else {
                $this->data = $value;
            }
        } else if ($args == 3 || $args == 2) {
            parent::outputCustomDataException(func_get_arg(0), func_get_arg(1));
            if ($args > 2) {
                $this->data = func_get_arg(2);
            }
        } else {
            exit('func param not match');
        }
    }

    /**
     * 返回对应数据
     * @return array|mixed
     */
    public function getData() {
        return $this->data;
    }
}