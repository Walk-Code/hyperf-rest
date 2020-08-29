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
use Hyperf\Paginator\Paginator;
use Hyperf\Utils\Collection;
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
        $currentPage = (int)$this->getParamDefaultValue('page', 1);
        $perPage = (int)$this->getParamDefaultValue('pageSize', 10);
        $collection = new Collection([
            ['id' => 1, 'name' => 'Tom'],
            ['id' => 2, 'name' => 'Sam'],
            ['id' => 3, 'name' => 'Tim'],
            ['id' => 4, 'name' => 'Joe'],
        ]);

        $users = array_values($collection->forPage($currentPage, $perPage)->toArray());

        return new Paginator($users, $perPage, $currentPage);
    }
}
