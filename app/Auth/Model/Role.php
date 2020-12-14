<?php
declare(strict_types=1);

namespace App\Auth\Model;

use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\DbConnection\Db;
use Hyperf\DbConnection\Model\Model;

class Role extends Model {
    protected $table = 'roles';

    protected $connection = 'system';

    /**
     * 获取菜单列表
     * @return array
     */
    public static function getList($name, $page, $pageSize): LengthAwarePaginatorInterface {
        $role = Role::query();
        if (!empty($name)) {
            $role->where('name', 'like', $name . '%');
        }
        $role->orderBy('updated_at', 'ASC');
        return $role->paginate($pageSize, ['*'], 'page', $page);
    }

    /**
     * 通过角色id获取角色菜单列表
     * @param $roleIds
     */
    public static function getRoleMenuList($roleIds) {
        $sql = 'SELECT T2.* FROM system_role T1 ';
        $sql .= 'LEFT JOIN admin_role_menu T2 ON T1.id = T2.role_id ';
        $sql .= 'WHERE T1.id IN (?) ';

        return Db::select($sql, [$roleIds]);
    }

    /**
     * 保存或者修改角色
     * @param array $data
     * @return bool
     */
    public static function saveOrUpdate($data = []) {
        $role = Role::query()->where('name', '=', $data['name'])
            ->orWhere('slug', '=', $data['slug'])
            ->first();
        if (!$role) {
            $role = new Role();
        }

        $role->name = $data['name'];
        $role->slug = $data['slug'];
        $role->updated_at = date('Y-m-d H:i:s');

        return $role->save();
    }

    /**
     * 通过id删除对应记录
     * @param $id
     */
    public static function deleteById($id) {
        return Role::query()->where('id', '=', $id)->delete();
    }

    /**
     * 通过id获取记录
     * @param $id
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    public static function findById($id) {
        return Role::query()->where('id', '=', $id)->first();
    }

    /**
     * 更新记录
     * @param array $data
     * @return bool|void
     */
    public static function updateData($data) {
        return Role::query()->where('id', '=', $data['id'])->update($data);
    }

}