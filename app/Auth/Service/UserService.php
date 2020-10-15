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

    
}