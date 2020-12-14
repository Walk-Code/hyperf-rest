<?php
declare(strict_types=1);

namespace App\Auth\Model;

use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Contract\PaginatorInterface;
use Hyperf\DbConnection\Db;
use Hyperf\DbConnection\Model\Model;
use Hyperf\Paginator\Paginator;
use PHPUnit\Util\RegularExpressionTest;

class Menu extends Model {
    protected $table = 'menu';

    protected $connection = 'system';

    public $timestamps = false;

    /**
     * 获取菜单列表
     * @return array
     */
    public static function getList($title, $page, $pageSize): LengthAwarePaginatorInterface {
        $menu = Menu::query();
        if (!empty($title)) {
            $menu->where('title', 'like', $title . '%');
        }

        return $menu->paginate((int)$pageSize, ['*'], 'page', (int)$page);
    }

    /**
     * 通过角色id获取角色菜单列表
     * @param $roleIds
     */
    public static function getRoleMenuList($roleIds) {
        $sql = 'SELECT T1.* FROM `admin_menu` T1 ';
        $sql .= 'LEFT JOIN admin_role_menu T2 ON T1.id = T2.menu_id ';
        $sql .= 'WHERE T2.role_id IN ( ? )';

        return Db::select($sql, [$roleIds]);
    }

    /**
     * Descripton: j
     * User: be
     * Date: 2020/12/8
     * Time: 13:55
     * @param array $data
     * @return bool
     */
    public static function saveAdminMemu($data = []) {
        $menu = new Menu();
        $menu->title = $data['title'];
        $menu->parent_code = $data['parent_code'];
        $menu->icon = $data['icon'];
        $menu->url = $data['url'];
        $menu->alias = $data['alias'];
        $menu->is_menu = $data['is_menu'];
        $menu->is_auth = $data['is_auth'];
        $menu->code = $data['code'];
        $menu->component = $data['component'];
        $menu->update_time = date('Y-m-d H:i:s');

        return $menu->save();
    }

    /**
     * 通过id删除对应记录
     * @param $id
     */
    public static function deleteById($id) {
        return Menu::query()->where('id', '=', $id)->delete();
    }

    /**
     * 获取所有菜单数据
     */
    public static function getAllData() {
        return Menu::query()->get()->toArray();
    }

    /**
     * 通过id获取记录
     * @param $id
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    public static function findById($id) {
        return Menu::query()->where('id', '=', $id)->first();
    }

    /**
     * 校验菜单是否存在
     * @param $ids
     */
    public static function checkMenuExtis($ids) {
        return Menu::query()->whereIn('id', $ids)->get()->toArray();
    }

    /**
     * Descripton: 通过系统用户id获取菜单列表
     * User: be
     * Date: 2020/11/19
     * Time: 11:53
     * @param $systemUserId
     */
    public static function getMenusBySystemUserId($systemUserId) {
        $sql = 'SELECT T1.*, T2.title parent_title FROM system_menu T1 ';
        $sql .= 'LEFT JOIN system_menu T2 ON T1.parent_code = T2.code ';
        $sql .= 'LEFT JOIN system_role_menu_mapping T3 ON T3.menu_id = T1.id ';
        $sql .= 'LEFT JOIN system_user_group_role T4 ON T4.role_id = T3.role_id ';
        $sql .= 'LEFT JOIN system_user_group_detail T5 ON T5.user_group_id = T4.user_group_id ';
        $sql .= 'LEFT JOIN system_user T6 ON T6.id = T5.user_id ';
        $sql .= 'WHERE T6.id =  ? ';
        $sql .= 'AND (T3.checked = 1 OR T3.indeterminate = 1) ';
        $sql .= 'AND T1.component != "" ';
        $sql .= 'GROUP BY T1.id ';
        $sql .= 'ORDER BY T1.code';

        return Db::select($sql, [$systemUserId]);
    }

    /**
     * Descripton: 获取不需要验证菜单列表
     * User: be
     * Date: 2020/11/19
     * Time: 12:00
     */
    public static function getMenusListNotAuth() {
        return Menu::query()->where('status', '=', 1)
            ->where('is_auth', '=', 2)
            ->where('component', '<>', '')
            ->get()->toArray();
    }

    /**
     * Descripton: 获取parent_code下所有权限
     * User: be
     * Date: 2020/11/19
     * Time: 12:14
     * @param $parentCode
     * @param $systemUserId
     * @return array
     */
    public static function getPermissionList($parentCode, $systemUserId) {
        $sql = 'SELECT T1.*, T2.title parent_title FROM system_menu T1 ';
        $sql .= 'LEFT JOIN system_menu T2 ON T1.parent_code = T2.code ';
        $sql .= 'LEFT JOIN system_role_menu_mapping T3 ON T3.menu_id = T1.id ';
        $sql .= 'LEFT JOIN system_user_group_role T4 ON T4.role_id = T3.role_id ';
        $sql .= 'LEFT JOIN system_user_group_detail T5 ON T5.user_group_id = T4.user_group_id ';
        $sql .= 'LEFT JOIN system_user T6 ON T6.id = T5.user_id ';
        $sql .= 'WHERE T1.is_auth = 1 ';
        $sql .= 'AND T6.id =  ? ';
        $sql .= 'AND T1.parent_code = ? ';
        $sql .= 'AND (T3.checked = 1 OR T3.indeterminate = 1) ';
        $sql .= 'AND T1.component != "" ';
        $sql .= 'GROUP BY T1.id ';
        $sql .= 'ORDER BY T1.code ';

        return Db::query($sql, [$systemUserId, $parentCode]);
    }

    /**
     * Descripton: 获取parent_code下所有无需鉴权的菜单
     * User: be
     * Date: 2020/11/19
     * Time: 13:58
     * @param $parentCode
     * @return \Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection
     */
    public static function getEnablePermissList($parentCode) {
        return Menu::where('parent_code', '=', $parentCode)
            ->where('is_auth', '=', 2)
            ->where('status', '=', 1)
            ->get()->toArray();
    }

    /**
     * Descripton: 获取按钮权限列表
     * User: be
     * Date: 2020/11/23
     * Time: 16:34
     * @param $systemUserId
     */
    public static function getBtnPermissionBySystemUserId($systemUserId) {
        $sql = 'SELECT T1.*, T2.title parent_title FROM system_menu T1 ';
        $sql .= 'LEFT JOIN system_menu T2 ON T1.parent_code = T2.code ';
        $sql .= 'LEFT JOIN system_role_menu_mapping T3 ON T3.menu_id = T1.id ';
        $sql .= 'LEFT JOIN system_user_group_role T4 ON T4.role_id = T3.role_id ';
        $sql .= 'LEFT JOIN system_user_group_detail T5 ON T5.user_group_id = T4.user_group_id ';
        $sql .= 'LEFT JOIN system_user T6 ON T6.id = T5.user_id ';
        $sql .= 'WHERE T6.id =  ? ';
        $sql .= 'AND T1.is_menu = 2 ';
        $sql .= 'GROUP BY T1.id ';
        $sql .= 'ORDER BY T1.code';

        return Db::select($sql, [$systemUserId]);
    }

    /**
     * Descripton: 获取启用的菜单数据
     * User: be
     * Date: 2020/12/7
     * Time: 17:35
     */
    public static function getEnAbleMenuData() {
        return Menu::query()->where('is_menu', '=', 1)->where('status', '=', 1)->get()->toArray();
    }

    /**
     * Descripton: 根据父节点获取最后子项
     * User: be
     * Date: 2020/12/8
     * Time: 17:22
     * @param $parentCode
     * @return array
     */
    public static function findLastRouteByParentCode($parentCode) {
        $data = Menu::query()->where('parent_code', '=', $parentCode)
            ->orderBy('code', 'desc')
            ->first()->toArray();

        return $data;
    }

    /**
     * Descripton: 更新菜单
     * User: be
     * Date: 2020/12/8
     * Time: 17:26
     */
    public static function updateD($id, $data) {
        return Menu::query()->where('id', '=', $id)->update($data);
    }

}