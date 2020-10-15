<?php


namespace App\Auth\Model;


use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\DbConnection\Model\Model;

class User extends Model {
    protected $table = 'users';

    protected $connection = 'system';

    /**
     * 校验系统用户是否存在
     * @param $ids
     */
    public static function checkUserExtis($ids) {
        return User::query()->whereIn('id', $ids)->get()->toArray();
    }

    /**
     * 获取列表数据
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
     * @param $data
     */
    public static function saveOrUpdate($data){
        $user = User::query()->where('username', '=', $data['username'])
            ->where('password', '=', $data['password'])
            ->first();

        if (!$user) {
            $user = new User();
        }

        $user->username = $data['username'];
        $user->password = $data['password'];
        $user->name = $data['name'];
        $user->avatar = $data['avatar'];
        $user->remember_token = $data['remember_token'];
        $user->updated_at = date('Y-m-d H:i:s');

        return $user->save();
    }

    /**
     * 通过用户名称获取记录
     * @param $username
     */
    public static function findByUserName($username) {
        return User::query()->where('username', '=', $username)->first();
    }

    /**
     * 通过id删除对应用户信息
     * @param $id
     */
    public static function deleteById($id) {
        return User::query()->where('id', '=', $id)->delete();
    }
}