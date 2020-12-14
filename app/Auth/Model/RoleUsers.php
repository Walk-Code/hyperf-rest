<?php


namespace App\Auth\Model;


use Hyperf\DbConnection\Model\Model;

class RoleUsers extends Model {
    protected $table = 'system_user_group_role';

    protected $connection = 'system';

    /**
     * 添加角色与菜单关系
     * @param $data
     */
    public static function saveOrUpdate($data) {
        $roleUsers= RoleUsers::query()->where('role_id', '=', $data['role_id'])
            ->where('user_id', '=', $data['user_id'])
            ->first();
        if (!$roleUsers) {
            $roleUsers= new RoleUsers();
        }
        $roleUsers->role_id = $data['role_id'];
        $roleUsers->menu_id = $data['user_id'];

        return $roleUsers->save();
    }

    /**
     * 通过角色id删除角色用户信息
     * @param $roleId
     */
    public static function deleteByRoleId($roleId) {
        return RoleUsers::query()->where('role_id', '=', $roleId)->delete();
    }

    /**
     * 通过角色id和用户id删除用户（支持多用户id）
     * @param $roleId
     * @param $menuIdArr
     */
    public static function deleteByRoleIdAndMenuId($roleId, $userIdArr) {
        return RoleUsers::query()->whereIn('user_id', $userIdArr)->where('role_id', '=', $roleId)->delete();
    }
}