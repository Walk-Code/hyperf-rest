<?php


namespace App\Auth\Model;


use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\DbConnection\Db;
use Hyperf\DbConnection\Model\Model;

class User extends Model {
    protected $table = 'user';

    protected $connection = 'system';

    /**
     * 校验系统用户是否存在
     *
     * @param $ids
     */
    public static function checkUserExtis($ids) {
        return User::query()->whereIn('id', $ids)->get()->toArray();
    }

    /**
     * 获取列表数据
     *
     * @param $searchText
     * @param $page
     * @param $pageSize
     */
    public static function getList($searchText, $page, $pageSize): LengthAwarePaginatorInterface {
        $user = User::query();
        if (!empty($searchText)) {
            $user->where('username', 'LIKE', $searchText . '%');
            $user->where('name', 'LIKE', $searchText . '%');
        }

        return $user->paginate($pageSize, ['*'], 'page', $page);
    }

    /**
     * 保存或者修改
     *
     * @param $data
     */
    public static function saveOrUpdate($data) {
        $user = User::query()->where('username', '=', $data['username'])
            ->where('password', '=', $data['password'])
            ->first();

        if (!$user) {
            $user = new User();
        }

        $user->username       = $data['username'];
        $user->password       = $data['password'];
        $user->name           = $data['name'];
        $user->avatar         = $data['avatar'];
        $user->remember_token = $data['remember_token'];
        $user->updated_at     = date('Y-m-d H:i:s');

        return $user->save();
    }

    /**
     * 通过用户名称获取记录
     *
     * @param $username
     */
    public static function findByUserName($username) {
        return User::query()->where('username', '=', $username)->first();
    }

    /**
     * 通过id删除对应用户信息
     *
     * @param $id
     */
    public static function deleteById($id) {
        return User::query()->where('id', '=', $id)->delete();
    }

    /**
     * 获取系统用户权限
     *
     * @param $systemUserId
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/10/20
     * Time: 17:38
     */
    public static function getPermission($systemUserId) {
        $sql     = 'SELECT T4.* FROM system_user T1 ';
        $sql     .= 'LEFT JOIN admin_role_users T2 ON T1.id = T2.user_id ';
        $sql     .= 'LEFT JOIN admin_role_menu T3 ON T3.role_id = T2.role_id ';
        $sql     .= 'LEFT JOIN admin_menu T4 ON T4.id = T3.menu_id ';
        $sql     .= 'WHERE T1.id = ? ';
        $sql     .= 'AND T4.status = 1; ';
        $data [] = $systemUserId;

        return Db::select($sql, $data);
    }

    /**
     * 通过id获取用户信息
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/11/13
     * Time: 15:51
     *
     * @param $id
     */
    public static function findById($userId) {
        return User::query()->where('id', '=', $userId)->first();
    }
}