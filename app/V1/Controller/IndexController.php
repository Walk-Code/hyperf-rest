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

namespace App\V1\Controller;

use App\Annotation\HttpRequestLog;
use App\Base\Controller\AbstractController;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

/**
 * Class IndexController
 * @package App\V1\Controller
 */
class IndexController extends AbstractController {

    /**
     * @var LoggerInterface
     */
    protected $logger;


    public function __construct(LoggerFactory $loggerFactory) {
        $this->logger = $loggerFactory->get(date('Y-m-d'), 'default');
    }

    public function index() {
        $argv = config('cors.origin');
        $this->responseSuccess($this->STATUS_200, '获取commands命令', [$argv]);
    }
}
