<?php

namespace App\Utils;

class LargeFileUploadHelper {
    /**
     * 合并文件
     * @param $originalFile
     * @param $mergeFile
     */
    public function fileMerge($originFilePath, $mergeFilePath) {
        $blob = file_get_contents($mergeFilePath);
        # TODO 获取路径下文件名称，若有存在待合并的文件则进行合并
        file_put_contents($originFilePath, $blob);
        @unlink($mergeFilePath);
    }
}