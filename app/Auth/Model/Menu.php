<?php
declare(strict_types=1);

namespace App\Auth\Model;

use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Contract\PaginatorInterface;
use Hyperf\DbConnection\Db;
use Hyperf\DbConnection\Model\Model;
use Hyperf\Paginator\Paginator;
use PHPUnit\Util\RegularExpressionTest;

class Menu extends Model {
    protected $table = 'menu';

    protected $connection = 'system';

    /**
     * 获取菜单列表
     * @return array
     */
    public static function getList($title, $page, $pageSize): LengthAwarePaginatorInterface {
        $menu = Menu::query();
        if (!empty($title)) {
            $menu->where('title', 'like', $title . '%');
        }
        $menu->orderBy('order', 'ASC');

        return $menu->paginate($pageSize, ['*'], 'page', $page);
    }

    /**
     * 通过角色id获取角色菜单列表
     * @param $roleIds
     */
    public static function getRoleMenuList($roleIds) {
        $sql = 'SELECT T1.* FROM `admin_menu` T1 ';
        $sql .= 'LEFT JOIN admin_role_menu T2 ON T1.id = T2.menu_id ';
        $sql .= 'WHERE T2.role_id IN ( ? )';

        return Db::select($sql, [$roleIds]);
    }


    public static function saveOrUpdate($data = []) {
        $menu = Menu::query()->where('id', '=', $data['id'])->first();
        if (!$menu) {
            $menu = new Menu();
        }

        $menu->id = $data['id'];
        $menu->parent_id = $data['parent_id'];
        $menu->order = $data['order'];
        $menu->title = $data['title'];
        $menu->icon = $data['icon'];
        $menu->uri = $data['uri'];
        $menu->updated_at = date('Y-m-d H:i:s');

        return $menu->save();
    }

    /**
     * 通过id删除对应记录
     * @param $id
     */
    public static function deleteById($id) {
        return Menu::query()->where('id', '=', $id)->delete();
    }

    /**
     * 获取所有菜单数据
     */
    public static function getAllData() {
        return Menu::query()->get()->toArray();
    }

    /**
     * 通过id获取记录
     * @param $id
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    public static function findById($id) {
        return Menu::query()->where('id', '=', $id)->first();
    }

    /**
     * 校验菜单是否存在
     * @param $ids
     */
    public static function checkMenuExtis($ids) {
        return Menu::query()->whereIn('id', $ids)->get()->toArray();
    }
}