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
use App\Utils\AuthorizationHelper;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class LoginServiceImpl implements LoginService {

    /**
     * @Inject
     * @var AdminUserPasswordHelper
     */
    private $adminUserPasswrodHelper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @Inject
     * @var AuthorizationHelper
     */
    private $authHelper;

    public function __construct(LoggerFactory $loggerFactory) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

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
        AssertsHelper::notNull($user, BusinessCode::getMessage(BusinessCode::AUTH_USER_NOT_FOUND));
        $encryptPassworded = $this->adminUserPasswrodHelper->createEncrypPassword($password);
        AssertsHelper::notNull($encryptPassworded == $user->password, BusinessCode::getMessage(BusinessCode::LOGIN_FAIL));
        unset($user->password);
        $this->authHelper->setUserInfo($user);
        $this->authHelper->setPermissionList($this->getPermission($user->id));

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