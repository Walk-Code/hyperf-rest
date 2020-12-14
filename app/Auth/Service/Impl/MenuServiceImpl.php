<?php


namespace App\Auth\Service\Impl;


use App\Auth\Model\Menu;
use App\Auth\Model\RoleMenu;
use App\Auth\Service\MenuService;
use App\Constants\ResponseCode;
use App\Exception\Utils\AssertsHelper;
use App\Utils\BuildTreeHelper;
use App\Utils\TreeHelper;

class MenuServiceImpl implements MenuService {

    /**
     * 获取列表数据
     * @param $searchText
     * @param $page
     * @param $pageSize
     * @return mixed
     */
    function getList($searchText, $page, $pageSize) {
        return Menu::getList($searchText, $page, $pageSize);
    }

    /**
     * 获取菜单树
     * @return mixed
     */
    public function toTree() {
        $data = Menu::getAllData();

        return TreeHelper::buildTree($data, 1000, 'parent_code', 'code');
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

    /**
     * Descripton: 通过系统用户id获取侧边栏列表
     * User: be
     * Date: 2020/11/19
     * Time: 11:45
     * @param $systemUserId
     * @return mixed
     */
    public function getIviewTreeData($systemUserId) {
        $data = Menu::getMenusBySystemUserId($systemUserId);
        $btnPermission = Menu::getBtnPermissionBySystemUserId($systemUserId);
        $data = self::dealDataWithLeftMenuTreeData($data, $btnPermission);

        return BuildTreeHelper::buildTree($data, 0);
    }

    /**
     * Descripton: 处理数据结构拼接成Left menus结构
     * User: be
     * Date: 2020/11/19
     * Time: 11:47
     * @param $data
     * @param $systemUserId
     * @return mixed
     */
    private function dealDataWithLeftMenuTreeData($data, $btnPermission) {
        // 添加permission 权限
        foreach ($data as $key => $item) {
            // 获取无需鉴权的菜单列表
            $meta = [
                'hideInMenu' => false,
                'icon' => $item->icon,
                'title' => $item->title,
                'notCache' => true,
                'permission' => $this->getBtnPermission($item->code, $btnPermission)
            ];

            if ($item->parent_code == 0) {
                $meta['showAlways'] = true;
            }

            $data[$key]->path = $item->url;
            $data[$key]->meta = $meta;
            $data[$key]->name = $item->title;
            unset($data[$key]->create_time);
            unset($data[$key]->update_time);
        }

        return $data;
    }

    /**
     * Descripton: 通过code获取按钮权限列表
     * User: be
     * Date: 2020/11/23
     * Time: 16:37
     * @param $code
     * @param $btnPermission
     * @return string
     */
    private function getBtnPermission($code, $btnPermission): string {
        $btnPermissionArr = [];
        foreach ($btnPermission as $item) {
            if ($code == $item->parent_code) {
                $btnPermissionArr[] = $item->alias;
            }
        }


        return implode(',', $btnPermissionArr);
    }

    /**
     * Descripton: 获取treeselect数据
     * User: be
     * Date: 2020/12/7
     * Time: 17:29
     * @param $systemUserId
     * @return mixed
     */
    public function getTreeSelectData($systemUserId) {
        $data = Menu::getEnAbleMenuData();
        $data = $this->dealTreeSelectData($data);
        $data = BuildTreeHelper::buildTree($data, 0);
        # 添加root路由
        $root['id'] = 0;
        $root['code'] = 0;
        $root['label'] = '根路由';
        $root['children'] = $data;

        return [$root];
    }

    /**
     * Descripton: 生成treeselect树
     * User: be
     * Date: 2020/12/7
     * Time: 17:59
     * @param array $data
     * @param $pid
     * @return array
     */
    private function dealTreeSelectData($data = []) {
        foreach ($data as $key => $item) {
            $data[$key]['id'] = $item['code'];
            $data[$key]['label'] = $item['title'];
        }

        return $data;
    }

    /**
     * Descripton: 添加后台系统菜单
     * User: be
     * Date: 2020/12/8
     * Time: 11:30
     * @return mixed
     */
    public function addOrUpdate($jsonArr) {
        if (isset($jsonArr['id']) && $jsonArr['id'] > 0) {
            $item = $jsonArr;
            unset($item['id']);
            unset($item['_index']);
            unset($item['_rowKey']);
            $item['update_time'] = date('Y-m-d H:i:s');
            $result = Menu::updateD($jsonArr['id'], $item);
            AssertsHelper::notNull($result, ResponseCode::getMessage(ResponseCode::FAILED));
        }else {
            AssertsHelper::notNull(isset($jsonArr['title']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['title']));
            AssertsHelper::notNull(isset($jsonArr['parent_code']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['parentCode']));
            AssertsHelper::notNull(isset($jsonArr['icon']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['icon']));
            AssertsHelper::notNull(isset($jsonArr['url']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['url']));
            AssertsHelper::notNull(isset($jsonArr['alias']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['alias']));
            AssertsHelper::notNull(isset($jsonArr['is_menu']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['is_menu']));
            AssertsHelper::notNull(isset($jsonArr['is_auth']), ResponseCode::getMessage(ResponseCode::SYSTEM_INVALID, ['is_auth']));
            $jsonArr['code'] = $this->generatorCode($jsonArr['parent_code']);
            $jsonArr['component'] = isset($jsonArr['component']) ? $jsonArr['component'] : '';
            $result = Menu::saveAdminMemu($jsonArr);
            AssertsHelper::notNull($result, ResponseCode::getMessage(ResponseCode::FAILED));
        }
    }

    /**
     * Descripton: 生成code
     * User: be
     * Date: 2020/12/8
     * Time: 11:48
     * @param $parentCode
     * @return int|mixed|string
     */
    private function generatorCode($parentCode) {
        $lastChildMenu = Menu::findLastRouteByParentCode($parentCode);
        if ($lastChildMenu) {
            return $lastChildMenu['code'] + 1;
        } else {
            $parentCode = empty($parentCode) ? '' : $parentCode;
            return $parentCode . '1000';
        }
    }

    /**
     * Descripton: 通过id删除
     * User: be
     * Date: 2020/12/8
     * Time: 17:51
     * @param $id
     * @return mixed
     */
    public function del($id) {
        $result = Menu::deleteById($id);
        AssertsHelper::notNull($result, ResponseCode::getMessage(ResponseCode::FAILED));
    }
}
