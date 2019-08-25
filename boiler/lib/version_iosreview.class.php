<?php

/**
 * version_iosreview.class.php ios提审类
 *
 * @version       v0.01
 * @create time   2018/8/18
 * @update time   2018/8/18
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Version_iosreview {

    public function __construct() {

    }

    /**
     * Version_iosreview::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_version_iosreview::add($attrs);

        return $id;
    }

    /**
     * Version_iosreview::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_version_iosreview::getInfoById($id);
    }

    /**
     * Version_iosreview::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_version_iosreview::update($id, $attrs);
    }

    /**
     * Version_iosreview::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_version_iosreview::del($id);
    }
}
?>