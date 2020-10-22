<?php


namespace App\Utils;

use Hyperf\Contract\SessionInterface;
use Hyperf\Di\Annotation\Inject;

/**
 * Class AuthorizationHelper
 *
 * @package App\Utils
 * description: 权限服务类
 * created on 2020/10/19 14:22
 * created by walk-code
 */
class AuthorizationHelper {
    // 用户权限
    private static $PERMISSION_KEY = 'USER-PERMISSION';

    // 用户角色
    private static $ROLE_KEY = 'USER-ROLE';

    // 用户接口列表
    private static $MENU_KEY = 'USER-MENU';

    // 用户信息
    private static $ADMIN_USER_KEY = 'USER-INFO';

    // 系统菜单列表
    private static $SYSTEM_ALL_MENU_KEY = 'SYSTEM-ALL-MENU-KEY';

    /**
     * @Inject
     * @var SessionInterface
     */
    private $session;

    /**
     * 获取系统接口列表
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/19
     * Time: 15:44
     *
     * @return string
     */
    public function getSystemAllMenuList(): string {
        return $this->session->get(self::$SYSTEM_ALL_MENU_KEY);
    }

    /**
     * 缓存系统接口
     *
     * @param array $systemAllMenuList
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/19
     * Time: 15:30
     */
    public function setSystemAllMenuList($systemAllMenuList = []): void {
        $this->session->set(self::$SYSTEM_ALL_MENU_KEY, $systemAllMenuList);
    }

    /**
     * 获取接口列表
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/19
     * Time: 15:44
     *
     * @return string
     */
    public function getMenuMoudleList(): string {
        return $this->session->get(self::$MENU_KEY);
    }

    /**
     * 缓存接口列表
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/19
     * Time: 15:34
     */
    public function setMenuMoudleList($menuModuleList = []): void {
        $this->session->set(self::$MENU_KEY, $menuModuleList);
    }

    /**
     * 获取用户信息
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/19
     * Time: 15:43
     *
     * @return object|null
     */
    public function getUserInfo(): ?object {
        $user = $this->session->get(self::$ADMIN_USER_KEY);

        return $user;
    }

    /**
     * 缓存用户信息
     *
     * @param $userInfo
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/19
     * Time: 15:38
     */
    public function setUserInfo($userInfo) {
        $this->session->set(self::$ADMIN_USER_KEY, $userInfo);
    }

    /**
     * 获取权限
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/19
     * Time: 15:43
     *
     * @return string
     */
    public function getPermissionList(): string {
        return $this->session->get(self::$PERMISSION_KEY);
    }

    /**
     * 缓存权限
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/19
     * Time: 15:46
     */
    public function setPermissionList($permissionList): void {
        $this->session->set(self::$PERMISSION_KEY, $permissionList);
    }

    /**
     * 校验是否登录
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/19
     * Time: 15:47
     *
     * @return bool
     */
    public function isLogin(): bool {
        return $this->getUserInfo() != null;
    }

    /**
     * 校验array中是否存在元素
     *
     * @param array $array
     * @param mixed ...$string
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/19
     * Time: 16:05
     * @return bool
     */
    private  function isExtisInAarry($array = [], ...$string): bool {
        foreach ($string as $s) {
            if (in_array($s, $array)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 校验权限
     * @param mixed ...$p
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/19
     * Time: 16:37
     * @return bool
     */
    public function isPermission(...$p) : bool {
        $permissionArr = $this->session->get(self::$PERMISSION_KEY);
        return $this->isRole('root') || $this->isExtisInAarry($permissionArr, $p);
    }

    /**
     * 校验角色
     * @param mixed ...$r
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/19
     * Time: 16:38
     */
    public function isRole(...$r) : bool {
        $roleArr = $this->session->get(self::$ROLE_KEY);

        return $this->isExtisInAarry($roleArr, $r);
    }
}