<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 2018/12/6
 * Time: 20:48
 */

class web_introduction
{
    /**
     * Web_introduction::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  table_web_introduction::add($attrs);
        return $id;
    }

    /**
     * Web_introduction::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return table_web_introduction::getInfoById($id);
    }

    /**
     * Web_introduction::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        $rs = table_web_introduction::update($id, $attrs);
        if($rs >= 0){
            return action_msg("修改成功！", 1);
        }else{
            return action_msg("修改失败！", 101);
        }
    }

    /**
     * Web_introduction::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return table_web_introduction::del($id);
    }

    /**
     * Web_introduction::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count){
        return Table_web_introduction::getPageList($page, $pageSize, $count);
    }
    static public function getList(){
        return Table_web_introduction::getList();
    }
    static public function getListQianDuan($params = array()){
        return Table_web_introduction::getListQianDuan($params);
    }
}