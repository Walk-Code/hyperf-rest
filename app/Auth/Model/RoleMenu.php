<?php


namespace App\Auth\Model;


use Hyperf\DbConnection\Model\Model;

class RoleMenu extends Model {
    protected $table = 'role_menu';

    protected $connection = 'system';

    /**
     * 添加角色与菜单关系
     * @param $data
     */
    public static function saveOrUpdate($data) {
        $roleMenu = RoleMenu::query()->where('role_id', '=', $data['role_id'])
            ->where('menu_id', '=', $data['menu_id'])
            ->first();
        if (!$roleMenu) {
            $roleMenu = new RoleMenu();
        }
        $roleMenu->role_id = $data['role_id'];
        $roleMenu->menu_id = $data['menu_id'];

        return $roleMenu->save();
    }

    /**
     * 通过角色id删除菜单
     * @param $roleId
     */
    public static function deleteByRoleId($roleId) {
        return RoleMenu::query()->where('role_id', '=', $roleId)->delete();
    }
}