<?php


namespace App\Exception\Core;

/**
 * 应用异常处理类
 * Class ApplicationException
 * @package App\Exception\Core
 */
class ApplicationException extends \RuntimeException {
    /**
     * 业务码
     * @var $code
     */
    protected $code;

    /**
     * 业务信息
     * @var $message
     */
    protected $message;

    /**
     * 输出对应业务信息
     * @param $message
     */
    function outputCustomMessagException($message) {
        parent::__construct($message);
        $this->message = $message;
    }

    /**
     * 输出对应业务码和信息
     * @param $code
     * @param $message
     */
    function outputCustomDataException($code, $message) {
        parent::__construct($message, $code);
        $this->code = $code;
        $this->message = $message;
    }

    function outputCustomMessageThrowable($message, Throwable $cause) {
        parent::__construct($message, $cause);
        $this->message = $message;
    }

    function outputThrowable(Throwable $cause) {
        parent::__construct($cause);
    }

}