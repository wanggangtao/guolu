<?php
/**
 * Created by PhpStorm.
 * User: imyuyang
 * Date: 2019-04-24
 * Time: 10:33
 */

class weixin_video
{
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_weixin_video::add($attrs);

        return $id;
    }


    static public function getInfoById($id){
        return Table_weixin_video::getInfoById($id);
    }


    static public function update($id, $attrs){
        return Table_weixin_video::update($id, $attrs);
    }


    static public function del($id){
        return Table_weixin_video::del($id);
    }

    static public function getListByProudctId($id){
        return Table_weixin_video::getListByProudctId($id);
    }

    static public function getListByProudctIdAndPage($id,$page,$pageSize){
        return Table_weixin_video::getListByProudctIdAndPage($id,$page,$pageSize);
    }

    static public function getCountByProductId($id,$page,$pageSize){
        return Table_weixin_video::getCountByProductId($id,$page,$pageSize);
    }


}