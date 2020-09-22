<?php


namespace App\Auth\Model;


use Hyperf\DbConnection\Model\Model;

class RoleUsers extends Model {
    protected $table = 'role_users';

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
}