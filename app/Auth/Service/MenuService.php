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
     * @param $title
     * @param $page
     * @param $pageSize
     * @return mixed
     */
    public function getList($title, $page, $pageSize);


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
    public function createOrEdit($jsonObj);
}