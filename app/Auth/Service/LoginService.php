<?php
/**
 * LoginService.php
 * description
 * created on 2020/10/20 11:18
 * created by walk-code
 */

namespace App\Auth\Service;


interface LoginService {

    /**
     *
     * @param $username
     * @param $password
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/20
     * Time: 11:35
     * @return mixed
     */
    public function loginValidate($username, $password);

    /**
     * 获取已启用的菜单
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/20
     * Time: 11:59
     *
     * @return mixed
     */
    public function getAllEnableMenu() ;


    /**
     * 通过系统id获取权限列表
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/20
     * Time: 12:02
     *
     * @return mixed
     */
    public function getPermission($systemUserId);
}