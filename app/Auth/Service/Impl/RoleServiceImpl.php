<?php


namespace App\Auth\Service\Impl;


use App\Auth\Model\Role;
use App\Auth\Model\RoleMenu;
use App\Auth\Model\RoleUsers;
use App\Auth\Service\RoleService;
use App\Constants\ResponseCode;
use App\Utils\AssertsHelper;

class RoleServiceImpl implements RoleService {

    /**
     * 获取列表数据
     * @param $title
     * @param $page
     * @param $pageSize
     * @return mixed
     */
    public function getList($name, $page, $pageSize) {
        return Role::getList($name, $page, $pageSize);
    }

    /**
     * 添加系统餐段，多个菜单使用逗号隔开
     * @param $data
     * @return mixed|void
     */
    public function addMenus($data) {
        AssertsHelper::notNull(isset($data['menuIds']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['menuIds']));
        AssertsHelper::notNull(isset($data['roleId']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['roleId']));
        $menuIdArr = explode(',', $data['menuIds']);
        AssertsHelper::notNull(count($menuIdArr) > 0, ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['menuIds']));

        foreach ($menuIdArr as $menuId) {
            $data['role_id'] = $data['roleId'];
            $data['menu_id'] = $menuId;
            $result = RoleMenu::saveOrUpdate($data);
            AssertsHelper::notNull($result, ResponseCode::FAILED);
        }
    }

    /**
     * 添加系统用户，多个系统用户使用逗号隔开
     * @param $userIds
     * @param $roleId
     * @return mixed
     */
    public function addAdminUsers($data) {
        AssertsHelper::notNull(isset($data['userIds']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['userIds']));
        AssertsHelper::notNull(isset($data['roleId']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['roleId']));
        $userIdArr = explode(',', $data['userIds']);
        AssertsHelper::notNull(count($userIdArr) > 0, ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['userIds']));

        foreach ($userIdArr as $userId) {
            $data['role_id'] = $data['roleId'];
            $data['menu_id'] = $userId;
            $result = RoleUsers::saveOrUpdate($data);
            AssertsHelper::notNull($result, ResponseCode::FAILED);
        }
    }

    /**
     * 创建角色
     * @param $jsonArr
     * @return mixed
     */
    public function create($jsonArr) {
        AssertsHelper::notNull(isset($jsonArr['name']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['name']));
        AssertsHelper::notNull(isset($jsonArr['slug']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['slug']));
        $result = Role::saveOrUpdate($jsonArr);
        AssertsHelper::notNull($result, ResponseCode::FAILED);
    }

}