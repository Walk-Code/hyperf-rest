<?php
/**
 * FileHelper.php
 * description
 * created on 2020/11/4 14:27
 * created by walk-code
 */

namespace App\Utils;


use App\Constants\BusinessCode;

class FileHelper {

    /**
     * 获取文件内容
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/11/4
     * Time: 14:31
     *
     * @param $filePath
     * @return string
     */
    public function getContent($filePath) {
        if (file_exists($filePath)) {
            $str = '';
            $fileArr = file($filePath);
            for($i = 0; $i < count($fileArr); $i++){
                $str .= $fileArr[$i];
            }

            return $str;
        }

        throw new  \RuntimeException(BusinessCode::getMessage(BusinessCode::FILE_NOT_FOUND), BusinessCode::FILE_NOT_FOUND);
    }
}