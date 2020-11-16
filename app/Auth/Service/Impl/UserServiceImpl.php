<?php


namespace App\Auth\Service\Impl;


use App\Auth\Model\User;
use App\Auth\Service\UserService;

class UserServiceImpl implements UserService {

    /**
     * @param $searchText
     * @param $page
     * @param $pageSize
     * @return mixed
     */
    public function getList($searchText, $page, $pageSize) {
        return User::getList($searchText, $page, $pageSize);
    }


    /**
     * 创建系统用户
     *
     * @param $jsonArr
     * @return mixed
     */
    public function create($jsonArr) {
        // TODO: Implement create() method.
    }

    /**
     * 通过用户id获取用户信息
     * Created by PhpStorm.
     * User: walk-code
     * Date: 2020/11/13
     * Time: 15:51
     *
     * @param $userId
     * @return mixed
     */
    public function find($userId) {
        return User::findById($userId);
    }
}