<?php


namespace App\Auth\Service;

/**
 * 系统菜单服务类
 * Interface MenuService
 * @package App\Auth\Service
 */
interface MenuService {

    /**
     * 获取列表数据
     * @param $searchText
     * @param $page
     * @param $pageSize
     * @return mixed
     */
    public function getList($searchText, $page, $pageSize);


    /**
     * 获取菜单树
     * @return mixed
     */
    public function toTree();


    /**
     * 创建或者编辑菜单
     * @param $id
     * @param $parentId
     * @param $order
     * @param $title
     * @param $icon
     * @param $url
     * @return mixed
     */
    public function createOrEdit($jsonArr);

    /**
     * 删除菜单
     * @param $id
     * @return mixed
     */
    public function delete($jsonArr);

    /**
     * Descripton: 通过系统用户id获取侧边栏列表
     * User: be
     * Date: 2020/11/19
     * Time: 11:45
     * @param $systemUserId
     * @return mixed
     */
    public function getIviewTreeData($systemUserId);


    /**
     * Descripton: 获取treeselect数据
     * User: be
     * Date: 2020/12/7
     * Time: 17:29
     * @param $systemUserId
     * @return mixed
     */
    public function getTreeSelectData($systemUserId);


    /**
     * Descripton: 添加后台系统菜单
     * User: be
     * Date: 2020/12/8
     * Time: 11:30
     * @return mixed
     */
    public function addOrUpdate($jsonArr);

    /**
     * Descripton: 通过id删除
     * User: be
     * Date: 2020/12/8
     * Time: 17:51
     * @param $id
     * @return mixed
     */
    public function del($id);
}