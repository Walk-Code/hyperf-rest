<?php


namespace App\Utils;


class TreeHelper {

    /**
     * 构建树
     * @param array $data
     * @param int $pidValue
     * @param string $parentNodeName
     * @param string $childNodeName
     * @return array
     */
    public static function buildTree(&$data = [], $pidValue = 0, $parentNodeName = 'parent_id', $childNodeName = 'id') {
        $tree = [];
        foreach ($data as $key => $item) {
            if ($item[$parentNodeName] == $pidValue) {
                $children = self::buildTree($data, $item[$childNodeName], $parentNodeName, $childNodeName);
                if ($children) {
                    $item['children'] = $children;
                }

                $tree[] = $item;
                unset($data[$item[$childNodeName]]);
            }
        }

        return $tree;
    }
}