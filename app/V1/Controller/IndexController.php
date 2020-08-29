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
use App\Utils\AssertsHelper;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\Redis;
use Hyperf\Utils\ApplicationContext;
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

    public function upload(Filesystem $filesystem) {
        // Process Upload
        $file = $this->request->file('upload');
        $fileName = $this->getParamDefaultValue('fileName', '');
        AssertsHelper::notNull($fileName, '');
        # 分片总数量
        $chunks = $this->getParamDefaultValue('chunks', 0);
        AssertsHelper::notNull($chunks, '分片总数不能为空');
        $ext = $file->getExtension();
        # 当分片总数只有2M或者小于两M时直接进行上床
        if ($chunks == 1) {
            $stream = fopen($file->getRealPath(), 'r+');
            $filesystem->writeStream(
                'uploads_tmp/' . $file->getClientFilename(),
                $stream
            );
            fclose($stream);
        } else {
            $chunk = (int)$this->getParamDefaultValue('chunk', 0);
            AssertsHelper::notNull($chunk, '分片数不能未空');
            $redis = ApplicationContext::getContainer()->get(Redis::class);
            $redisKey = $file->$fileName;
            $tmpPath = 'uploads_tmp/' . $file->getClientFilename() . '_' . $chunk;
            $stream = fopen($file->getRealPath(), 'r+');
            $isSuccess = $filesystem->writeStream($tmpPath, $stream);
            fclose($stream);
            # 临时文件过期时间为1H
            if ($isSuccess) {
                $redis->setTimeout($redisKey, 3600);
                $redis->zAdd($redisKey, [], $chunk, $tmpPath);
                $uploadCount = $redis->zCard($redisKey);
                # 分片上传完后进行分片合并
                $originFile = 'uploads/'.$fileName;
                $getAllFragmentization = $redis->zRange($redisKey, 0, -1);
                if ($getAllFragmentization && is_array($getAllFragmentization)) {
                    foreach ($getAllFragmentization as $temp) {
                        $tempStream = fopen($temp, 'r+');
                        $result = $filesystem->writeStream($originFile, $tempStream);
                        if (is_resource($stream)) {
                            fclose($stream);
                        }
                        if ($result){
                            @unlink($temp);
                        }
                    }
                }
            }
        }


        $this->responseSuccess($this->STATUS_200, '操作成功。');
    }
}
