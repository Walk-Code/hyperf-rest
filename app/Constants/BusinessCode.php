<?php


namespace App\Constants;


use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class BusinessCode extends AbstractConstants {
    /**
     * @Message("Login failed")
     */
    const LOGIN_FAIL = 10001;
}