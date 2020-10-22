<?php
/**
 * LoginServiceImpl.php
 * description
 * created on 2020/10/20 12:04
 * created by walk-code
 */

namespace App\Auth\Service\Impl;


use App\Auth\Model\User;
use App\Auth\Service\LoginService;
use App\Constants\BusinessCode;
use App\Constants\ResponseCode;
use App\Exception\Utils\AssertsHelper;
use App\Utils\AdminUserPasswordHelper;
use Hyperf\Di\Annotation\Inject;

class LoginServiceImpl implements LoginService {

    /**
     * @Inject
     * @var AdminUserPasswordHelper
     */
    private $adminUserPasswrodHelper;

    /**
     * 登录验证
     *
     * @param $username
     * @param $password
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/20
     * Time: 14:52
     * @return mixed|void
     */
    public function loginValidate($username, $password) {
        $user = User::findByUserName($username);
        AssertsHelper::notNull($user, ResponseCode::getMessage(ResponseCode::NO_FOUND));
        $encryptPassworded = $this->adminUserPasswrodHelper->createEncrypPassword($password);
        AssertsHelper::notNull($encryptPassworded == $user->password, BusinessCode::getMessage(BusinessCode::LOGIN_FAIL));

        return $user;
    }

    /**
     * 获取启用接口列表
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/20
     * Time: 14:53
     *
     * @return mixed|void
     */
    public function getAllEnableMenu() {

    }

    /**
     * 获取权限列表
     *
     * @param $systemUserId
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/20
     * Time: 14:53
     * @return mixed|void
     */
    public function getPermission($systemUserId) {
        return User::getPermission($systemUserId);
    }
}