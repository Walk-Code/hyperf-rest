<?php

declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class ResponseCode extends AbstractConstants {
    /**
     * @Message("Server Error！")
     */
    const SERVER_ERROR = 500;

    /**
     * @Message("Params %s is invalid.")
     */
    const SYSTEM_INVALID = 422;

    /**
     * @Message("operation successful.")
     */
    const SUCCESS = 200;

    /**
     * @Message("operation failed.")
     */
    const FAILED = 500;

    /**
     * @Message("not found")
     */
    const NO_FOUND = 404;

    /**
     * @Message("Unauthorized")
     */
    const NO_AUTH = 401;
}
