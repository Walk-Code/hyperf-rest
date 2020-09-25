<?php


namespace App\Auth\Model;


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
}