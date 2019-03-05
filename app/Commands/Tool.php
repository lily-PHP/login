<?php

/**
 * @Created by PhpStorm.
 * @author chenli <491126240@qq.com>
 * @Date: 2019/3/4
 * @Time: 14:57
 */

namespace App\Commands;


class Tool
{

    /**
     * 将一个平面的二维数组按照指定的字段转换为树状结构
     *
     * 用法：
     * <code>
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1', 'parent' => 0),
     *     array('id' => 2, 'value' => '2-1', 'parent' => 0),
     *     array('id' => 3, 'value' => '3-1', 'parent' => 0),
     *
     *     array('id' => 7, 'value' => '2-1-1', 'parent' => 2),
     *     array('id' => 8, 'value' => '2-1-2', 'parent' => 2),
     *     array('id' => 9, 'value' => '3-1-1', 'parent' => 3),
     *     array('id' => 10, 'value' => '3-1-1-1', 'parent' => 9),
     * );
     *
     * $tree = Tool::tree($rows, 'id', 'parent', 'nodes');
     *
     * print_r($tree);
     *   // 输出结果为：
     *   // array(
     *   //   array('id' => 1, ..., 'nodes' => array()),
     *   //   array('id' => 2, ..., 'nodes' => array(
     *   //        array(..., 'parent' => 2, 'nodes' => array()),
     *   //        array(..., 'parent' => 2, 'nodes' => array()),
     *   //   ),
     *   //   array('id' => 3, ..., 'nodes' => array(
     *   //        array('id' => 9, ..., 'parent' => 3, 'nodes' => array(
     *   //             array(..., , 'parent' => 9, 'nodes' => array(),
     *   //        ),
     *   //   ),
     *   // )
     * </code>
     *
     * 如果要获得任意节点为根的子树，可以使用 $refs 参数：
     * <code>
     * $refs = null;
     * $tree = Tool::tree($rows, 'id', 'parent', 'nodes', $refs);
     *
     * // 输出 id 为 3 的节点及其所有子节点
     * $id = 3;
     * print_r($refs[$id]);
     * </code>
     *
     * @param array $arr 数据源
     * @param string $key_node_id 节点ID字段名
     * @param string $key_parent_id 节点父ID字段名
     * @param string $key_childrens 保存子节点的字段名
     * @param boolean $refs 是否在返回结果中包含节点引用
     *
     * return array 树形结构的数组
     */
    static function tree($arr, $key_node_id, $key_parent_id = 'parent_id', $key_childrens = 'childrens', & $refs = null) {
        $refs = array();
        foreach ($arr as $offset => $row) {
            $arr[$offset][$key_childrens] = array();
            $refs[$row[$key_node_id]] = & $arr[$offset];
        }

        $tree = array();
        foreach ($arr as $offset => $row) {
            $parent_id = $row[$key_parent_id];
            if ($parent_id) {
                if (!isset($refs[$parent_id])) {
                    $tree[]                   = & $arr[$offset];
                    continue;
                }
                $parent                   = & $refs[$parent_id];
                $parent[$key_childrens][] = & $arr[$offset];
            } else {
                $tree[] = & $arr[$offset];
            }
        }

        return $tree;
    }


    /**
     * 将树形数组展开为平面的数组
     *
     * 这个方法是 tree() 方法的逆向操作。
     *
     * @param array $tree 树形数组
     * @param string $key_childrens 包含子节点的键名
     *
     * @return array 展开后的数组
     */
    static function treeToArray($tree, $key_childrens = 'childrens') {
        $ret = array();
        if (isset($tree[$key_childrens]) && is_array($tree[$key_childrens])) {
            $childrens = $tree[$key_childrens];
            unset($tree[$key_childrens]);
            $ret[] = $tree;
            foreach ($childrens as $node) {
                $ret = array_merge($ret, self::treeToArray($node, $key_childrens));
            }
        } else {
            unset($tree[$key_childrens]);
            $ret[] = $tree;
        }
        return $ret;
    }
}