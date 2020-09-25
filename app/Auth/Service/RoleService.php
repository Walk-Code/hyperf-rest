<?php


namespace App\Auth\Service;


interface RoleService {

    /**
     * 获取列表数据
     * @param $title
     * @param $page
     * @param $pageSize
     * @return mixed
     */
    public function getList($name, $page, $pageSize);

    /**
     * 添加系统餐段，多个菜单使用逗号隔开
     * @param $data
     * @return mixed
     */
    public function addMenus($data);

    /**
     * 添加系统用户，多个系统用户使用逗号隔开
     * @param $userIds
     * @param $roleId
     * @return mixed
     */
    public function addAdminUsers($data);

    /**
     * 创建角色
     * @param $jsonArr
     * @return mixed
     */
    public function create($jsonArr);

    /**
     * 修改角色
     * @param $jsonArr
     * @return mixed
     */
    public function update($jsonArr);


    /**
     * 删除角色
     * @param $id
     * @return mixed
     */
    public function delete($jsonArr);
}