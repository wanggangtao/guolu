<?php

/**
 * web_contactus.class.php 联系我们类
 *
 * @version       v0.01
 * @create time   2018/11/21
 * @update time   2018/11/21
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Web_contactus {

    /**
     * Web_contactus::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  table_web_contactus::add($attrs);
        return $id;
    }

    /**
     * Web_contactus::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return table_web_contactus::getInfoById($id);
    }

    static public function getList($params=array()){
        return table_web_contactus::getList($params);
    }


    static public function update($id, $attrs){
        return Table_web_contactus::update($id, $attrs);
    }


}
?>