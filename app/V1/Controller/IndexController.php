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

use App\Base\Controller\AbstractController;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

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
        $this->logger->debug('Your log message.');
        $this->responseSuccess($this->STATUS_200, '获取commands命令', [$argv]);
    }
}
