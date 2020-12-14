<?php


namespace App\Utils;


use think\facade\Log;

/**
 * 生成tree 结构data
 * Class BuildTreeHelper
 * @package App\Utils
 */
class BuildTreeHelper {

    /**
     * Descripton: 根据数据id名生成树
     * User: be
     * Date: 2020/11/19
     * Time: 11:48
     * @param array $data
     * @param int $pidValue
     * @return array
     */
    public static function buildTree(&$data = [], $pidValue = 0) {
        $tree = [];
        foreach ($data as $key=>$item) {
            if (!is_array($item)) {
                $item = (array)$item;
            }

            if ($item['parent_code'] == $pidValue) {
                $children = self::buildTree($data, $item['code']);
                if ($children) {
                    $item['children'] = $children;
                }

                $tree[] = $item;
                unset($data[$item['code']]);
            }
        }

        return $tree;
    }
}