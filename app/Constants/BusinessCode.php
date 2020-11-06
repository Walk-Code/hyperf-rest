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

    /**
     * @Message("system user not found")
     */
    const AUTH_USER_NOT_FOUND = 10002;

    /**
     * @Message("file not found")
     */
    const FILE_NOT_FOUND = 10003;

    /**
     * @Message("token is invalid")
     */
    const TOKEN_IS_INVALID = 10004;

    /**
     * @Message("token signature invalid");
     */
    const TOKEN_SIGNATURE_INVALID = 10005;

    /**
     * @Message("token expired")
     */
    const TOKEN_EXPIRED = 10006;
}