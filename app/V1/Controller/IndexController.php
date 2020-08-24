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
use App\Exception\Utils\AssertsHelper;
use Hyperf\Config\Annotation\Value;

class IndexController extends AbstractController {

    public function index() {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        $argv = getopt('D:');
        $this->responseSuccess($this->STATUS_200, '获取commands命令', $argv);
    }
}
