<?php
/**
 * AdminUserPasswordHelper.php
 * description
 * created on 2020/10/20 16:40
 * created by walk-code
 */

namespace App\Utils;


class AdminUserPasswordHelper {
    const SALT = '<~!@#$%^&*()?>';

    /**
     * 获取加密字符串
     * @param $password
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/20
     * Time: 16:53
     * @return string
     */
    public function createEncrypPassword($password): string {
        return md5(self::SALT . $password);
    }
}