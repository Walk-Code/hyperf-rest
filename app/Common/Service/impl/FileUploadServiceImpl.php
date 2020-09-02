<?php


use App\Common\Service\FileUploadService;
use App\Exception\Utils\AssertsHelper;
use Hyperf\Utils\ApplicationContext;

class FileUploadServiceImpl implements FileUploadService {

    /**
     * 上传文件（支持大文件上传）
     * @param $file
     * @param $chunks
     * @param $chunk
     * @param $fileName
     * @return mixed
     */
    public function upload($file, $chunks, $chunk, $fileName) {
        # 当分片总数只有2M或者小于两M时直接进行上传
        if ($chunks == 1) {
            $stream = fopen($file->getRealPath(), 'r+');
            $filesystem->writeStream(
                'uploads/' . $file->getClientFilename(),
                $stream
            );
            fclose($stream);
            return true;
        } else {
            $chunk = (int)$this->request->input('chunk', 0);
            AssertsHelper::notNull($chunk, '分片数不能未空');
            $redis     = ApplicationContext::getContainer()->get(Redis::class);
            $redisKey  = $fileName;
            $tmpPath   = 'uploads_tmp/' . $fileName . '_' . $chunk;
            $stream    = fopen($file->getRealPath(), 'r+');
            $isSuccess = $filesystem->writeStream($tmpPath, $stream);
            fclose($stream);
            # 临时文件过期时间为1H
            if ($isSuccess) {
                $redis->expire($redisKey, 3600);
                $redis->zAdd($redisKey, [], $chunk, $tmpPath);
                $uploadCount = $redis->zCard($redisKey);
                # 分片上传完后进行分片合并
                $originDir = BASE_PATH . '/runtime/'.'uploads/';
                $originFilePath            = $originDir . $fileName;
                if (!is_dir($originDir)) {
                    mkdir($originDir);
                }
                $originFilef = fopen($originFilePath,"w+");
                AssertsHelper::notNull($originFilef, '打开文件失败。');
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
    }
}