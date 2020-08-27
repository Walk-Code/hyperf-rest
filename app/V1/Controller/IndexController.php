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
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Logger\LoggerFactory;
use League\Flysystem\Filesystem;
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

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    public function __construct(LoggerFactory $loggerFactory) {
        $this->logger = $loggerFactory->get(date('Y-m-d'), 'default');
    }

    public function index() {
        $argv = config('cors.origin');
        $this->responseSuccess($this->STATUS_200, '获取commands命令', [$argv]);
    }

    public function upload(Filesystem $filesystem){
        // Process Upload
        //$this->responseSuccess($this->STATUS_200, '操作成功。', $request->hasFile('upload'));
        $file = $this->request->file('upload');
        $stream = fopen($file->getRealPath(), 'r+');
        $filesystem->writeStream(
            'uploads/'.$file->getClientFilename(),
            $stream
        );
        fclose($stream);

        $this->responseSuccess($this->STATUS_200, '操作成功。');
    }
}
