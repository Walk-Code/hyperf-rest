<?php


namespace App\Auth\Service\Impl;


use App\Auth\Model\Menu;
use App\Auth\Model\Role;
use App\Auth\Model\RoleMenu;
use App\Auth\Model\RoleUsers;
use App\Auth\Model\User;
use App\Auth\Service\RoleService;
use App\Constants\ResponseCode;
use App\Exception\Utils\AssertsHelper;
use Hyperf\DbConnection\Db;

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
        $menuExtis = Menu::checkMenuExtis($menuIdArr);
        AssertsHelper::notNull(count($menuExtis) == count($menuIdArr), ResponseCode::getMessage(ResponseCode::FAILED));

        foreach ($menuIdArr as $menuId) {
            $data['role_id'] = $data['roleId'];
            $data['menu_id'] = $menuId;
            $result = RoleMenu::saveOrUpdate($data);
            AssertsHelper::notNull($result, ResponseCode::getMessage(ResponseCode::FAILED));
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
        $userExtis = User::checkUserExtis($userIdArr);
        AssertsHelper::notNull(count($userExtis) == count($userIdArr), ResponseCode::getMessage(ResponseCode::FAILED));

        foreach ($userIdArr as $userId) {
            $data['role_id'] = $data['roleId'];
            $data['menu_id'] = $userId;
            $result = RoleUsers::saveOrUpdate($data);
            AssertsHelper::notNull($result, ResponseCode::getMessage(ResponseCode::FAILED));
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
        AssertsHelper::notNull($result, ResponseCode::getMessage(ResponseCode::FAILED));
    }

    /**
     * 修改角色
     * @param $jsonArr
     * @return mixed
     */
    public function update($jsonArr) {
        AssertsHelper::notNull(isset($jsonArr['id']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['id']));
        AssertsHelper::notNull(isset($jsonArr['name']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['name']));
        AssertsHelper::notNull(isset($jsonArr['slug']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['slug']));
        $jsonArr['updated_at'] = date('Y-m-d H:i:s');
        $result = Role::updateData($jsonArr);
        AssertsHelper::notNull($result, ResponseCode::getMessage(ResponseCode::FAILED));
    }

    /**
     * 删除角色
     * @param $id
     * @return mixed
     */
    public function delete($jsonArr) {
        AssertsHelper::notNull(isset($jsonArr['id']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['id']));
        $id = $jsonArr['id'];
        Db::connection('system')->transaction(function () use ($id) {
            AssertsHelper::notNull($id, ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['id']));
            $delMenuResult = RoleMenu::deleteByRoleId($id);
            AssertsHelper::notNull($delMenuResult, ResponseCode::getMessage(ResponseCode::FAILED));

            $delUserResult = RoleUsers::deleteByRoleId($id);
            AssertsHelper::notNull($delUserResult, ResponseCode::getMessage(ResponseCode::FAILED));

            $result = Role::deleteById($id);
            AssertsHelper::notNull($result, ResponseCode::getMessage(ResponseCode::FAILED));
        });
    }

    /**
     * 删除角色对应菜单
     * @param $jsonArr
     * @return mixed
     */
    public function deleteMenus($jsonArr) {
        $data = $jsonArr;
        AssertsHelper::notNull(isset($data['menuIds']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['menuIds']));
        AssertsHelper::notNull(isset($data['roleId']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['roleId']));
        $menuIdArr = explode(',', $data['menuIds']);
        $result = RoleMenu::deleteByRoleIdAndMenuId($data['roleId'], $menuIdArr);
        AssertsHelper::notNull($result, ResponseCode::getMessage(ResponseCode::FAILED));
    }

    /**
     * 删除角色对应用户
     * @param $jsonArr
     * @return mixed
     */
    public function deleteUsers($jsonArr) {
        $data = $jsonArr;
        AssertsHelper::notNull(isset($data['userIds']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['userIds']));
        AssertsHelper::notNull(isset($data['roleId']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['roleId']));
        $userIdArr = explode(',', $data['userIds']);
        $result = RoleUsers::deleteByRoleIdAndMenuId($data['roleId'], $userIdArr);
        AssertsHelper::notNull($result, ResponseCode::getMessage(ResponseCode::FAILED));
    }
}