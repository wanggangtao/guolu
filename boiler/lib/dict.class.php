<?php

/**
 * dict.class.php 产品类别类
 *
 * @version       v0.01
 * @create time   2018/5/27
 * @update time   2018/5/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Dict {

    public function __construct() {

    }

    /**
     * Dict::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_dict::add($attrs);

        return $id;
    }

    /**
     * Dict::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_dict::getInfoById($id);
    }

    /**
     * Dict::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_dict::update($id, $attrs);
    }

    /**
     * Dict::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_dict::del($id);
    }

    /**
     * Dict::delByParentid() 根据父类ID删除数据
     * @param $parentid  父类别ID
     * @return mixed
     */
    static public function delByParentid($parentid){
        return Table_dict::delByParentid($parentid);
    }

    /**
     * Dict::getInfoByParentid() 根据父类ID获取详情
     * @param $parentid   父类别ID
     * @return mixed
     */
    static public function getInfoByParentid($parentid){
        return Table_dict::getInfoByParentid($parentid);
    }

    /**
     * Dict::getListByParentid() 根据父类ID获取详情
     * @param $parentid   父类别ID
     * @return mixed
     */
    static public function getListByParentid($parentid){
    	return Table_dict::getListByParentid($parentid);
    }


    /**
     * Dict::getListByParentid() 根据类别获取详情
     * @param $parentid  父类别ID
     * @param $cat   子类别ID
     * @return mixed
     */

    static public function getListByCat($parentid,$cat=1){
        if($cat !== 1 && $cat !== 2) return '参数不合法';
        return Table_dict::getListByCat($parentid,$cat);
    }


    /**
     * Dict::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $parentid         父类ID
     * @param number $status           1是可使用 0禁用
     * @param number $model            关联的model表的id
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $parentid, $status, $model){
        return Table_dict::getPageList($page, $pageSize, $count, $parentid, $status, $model);
    }
    static public function getInfoByName($name){
        return Table_dict::getInfoByName($name);
    }
    static public function getListByPid($parentid,$type=0){
        return Table_dict::getListByPid($parentid,$type);
    }
}
?>