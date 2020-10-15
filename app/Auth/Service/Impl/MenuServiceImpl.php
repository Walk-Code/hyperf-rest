<?php


namespace App\Auth\Service\Impl;


use App\Auth\Model\Menu;
use App\Auth\Model\RoleMenu;
use App\Auth\Service\MenuService;
use App\Constants\ResponseCode;
use App\Exception\Utils\AssertsHelper;
use App\Utils\TreeHelper;

class MenuServiceImpl implements MenuService {

    /**
     * 获取列表数据
     * @return mixed
     */
    public function getList($title, $page, $pageSize) {
        return Menu::getList($title, $page, $pageSize);
    }

    /**
     * 获取菜单树
     * @return mixed
     */
    public function toTree() {
        $data = Menu::getAllData();

        return TreeHelper::buildTree($data);
    }


    /**
     * 创建或者编辑菜单
     * @param $jsonObj
     * @param $type 1-create, 2-edit
     * @return mixed|void
     */
    public function createOrEdit($jsonArr) {
        AssertsHelper::notNull(isset($jsonArr['type']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['type']));
        // 需要校验菜单是否存在
        if ($jsonArr['type'] == 2) {
            AssertsHelper::notNull(isset($jsonArr['id']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['id']));
            $isExtis = Menu::findById($jsonArr['id']);
            AssertsHelper::notNull($isExtis, ResponseCode::getMessage(ResponseCode::NO_FOUND));
        } else {
            $jsonArr['id'] = 0;
        }
        AssertsHelper::notNull(isset($jsonArr['title']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['title']));
        AssertsHelper::notNull(isset($jsonArr['parent_id']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['parentId']));
        AssertsHelper::notNull(isset($jsonArr['order']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['order']));
        AssertsHelper::notNull(isset($jsonArr['icon']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['icon']));
        AssertsHelper::notNull(isset($jsonArr['uri']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['uri']));
        $result = Menu::saveOrUpdate($jsonArr);
        AssertsHelper::notNull($result, ResponseCode::getMessage(ResponseCode::FAILED));
    }

    /**
     * 删除菜单
     * @param $id
     * @return mixed
     */
    public function delete($jsonArr) {
        AssertsHelper::notNull(isset($data['id']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['id']));
        $isExtis = Menu::findById($jsonArr['id']);
        AssertsHelper::notNull($isExtis, ResponseCode::getMessage(ResponseCode::NO_FOUND));
        RoleMenu::deleteByMenuId($jsonArr['id']);
        Menu::deleteById($jsonArr['id']);
    }
}