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


}