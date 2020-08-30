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
use Hyperf\Di\Annotation\Inject;
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
class IndexController extends AbstractController {// TODO 临时文件未删除
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
        $this->logger = $loggerFactory->get('default');
    }

    public function index(Filesystem $filesystem) {
        $redis                 = ApplicationContext::getContainer()->get(Redis::class);
        $fileName = 'apache-jmeter-4.0.rar';
        $getAllFragmentization = $redis->zRange($fileName, 0, -1);
        # 分片上传完后进行分片合并
        $originFilePath            = BASE_PATH . '/runtime/'.'uploads/' . $fileName;
        $originFilef = fopen($originFilePath,"w+");
        AssertsHelper::notNull($originFilef, '打开文件失败。');
        $getAllFragmentization = $redis->zRange($fileName, 0, -1);
        if ($getAllFragmentization && is_array($getAllFragmentization)) {
            foreach ($getAllFragmentization as $temp) {
                $tempPath       = BASE_PATH . '/runtime/' . $temp;
                $tempf = fopen($tempPath, 'rb');
                $content = fread($tempf, filesize($tempPath));
                fwrite($originFilef, $content);
                unset($content);
                fclose($tempf);
                @unlink($tempPath);
            }
        }
        fclose($originFilef);
        $this->responseSuccess($this->STATUS_200, '获取commands命令 ' . BASE_PATH);
    }

    public function upload(Filesystem $filesystem) {
        // Process Upload
        $file = $this->request->file('upload');
        AssertsHelper::notNull($this->request->file('upload')->isValid(), '文件不存在');
        $fileName = $this->request->input('fileName', '');
        AssertsHelper::notNull($fileName, '文件名不能为空');
        # 分片总数量
        $chunks = $this->request->input('chunks', 0);
        AssertsHelper::notNull($chunks, '分片总数不能为空');
        # 当分片总数只有2M或者小于两M时直接进行上床
        if ($chunks == 1) {
            $stream = fopen($file->getRealPath(), 'r+');
            $filesystem->writeStream(
                'uploads/' . $file->getClientFilename(),
                $stream
            );
            fclose($stream);
            $this->responseSuccess($this->STATUS_200, '操作成功。');
        } else {
            $chunk = (int)$this->request->input('chunk', 0);
            AssertsHelper::notNull($chunk, '分片数不能未空');
            $redis     = ApplicationContext::getContainer()->get(Redis::class);
            $redisKey  = $fileName;
            $tmpPath   = 'uploads_tmp/' . $file->getClientFilename() . '_' . $chunk;
            $stream    = fopen($file->getRealPath(), 'r+');
            $isSuccess = $filesystem->writeStream($tmpPath, $stream);
            fclose($stream);
            # 临时文件过期时间为1H
            if ($isSuccess) {
                $redis->expire($redisKey, 3600);
                $redis->zAdd($redisKey, [], $chunk, $tmpPath);
                $uploadCount = $redis->zCard($redisKey);
                # 分片上传完后进行分片合并
                $originFilePath            = BASE_PATH . '/runtime/'.'uploads/' . $fileName;
                $originFilef = fopen($originFilePath,"w+");
                $getAllFragmentization = $redis->zRange($redisKey, 0, -1);
                if ($getAllFragmentization && is_array($getAllFragmentization)) {
                    foreach ($getAllFragmentization as $temp) {
                        $tempPath       = BASE_PATH . '/runtime/' . $temp;
                        $tempf = fopen($tempPath, 'rb');
                        $content = fread($tempf, filesize($tempPath));
                        fwrite($originFilef, $content);
                        unset($content);
                        fclose($tempf);
                    }
                }
                fclose($originFilef);
            }
        }

        $this->responseSuccess($this->STATUS_200, '操作成功。');
    }
}