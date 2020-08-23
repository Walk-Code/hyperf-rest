<?php


namespace App\Base\Controller;


use App\Exception\Utils\ResponseHelper;

abstract class AbstractRestController {
    /**
     * 业务成功状态码
     * @var int
     */
    protected $STATUS_200 = 200;

    /**
     * 业务失败状态码
     * @var int
     */
    protected $STATUS_500 = 500;

    /**
     * 未授权状态码
     * @var int
     */
    protected $STATUS_401 = 401;


    /**
     * 返回成功响应状态信息
     * @param $msg
     */
    public function responseSuccess($code, $msg, $data = []) {
        ResponseHelper::success($this->STATUS_200, $msg, $data);
    }

    /**
     * 返回错误响应信息
     */
    public function responseFailure($code, $msg) {
        ResponseHelper::fail($code, $msg);
    }
}