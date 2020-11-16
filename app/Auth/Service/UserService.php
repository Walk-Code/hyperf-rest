<?php


namespace App\Auth\Service;


interface UserService {

    /**
     * @param $searchText
     * @param $page
     * @param $pageSize
     * @return mixed
     */
    public function getList($searchText, $page, $pageSize);

    /**
     * 创建系统用户
     * @param $jsonArr
     * @return mixed
     */
    public function create($jsonArr);


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
    public function find($userId);
}