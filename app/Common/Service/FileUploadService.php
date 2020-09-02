<?php


namespace App\Common\Service;


interface FileUploadService {

    /**
     * 上传文件（支持大文件上传）
     * @param $file
     * @param $chunks
     * @param $chunk
     * @param $fileName
     * @return mixed
     */
    public function upload($file, $chunks, $chunk, $fileName);
}