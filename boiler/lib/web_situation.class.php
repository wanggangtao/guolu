<?php
/**
 * web_situation: 公司动态类
 * Created by kjb.
 * Date: 2018/12/5
 * Time: 10:08
 */

class Web_situation
{
    /**
     * Web_situation::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_web_situation::add($attrs);
        return $id;
    }

    /**
     * Web_situation::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_web_situation::getInfoById($id);
    }

    /**
     * Web_situation::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        $rs = Table_web_situation::update($id, $attrs);
        if($rs >= 0){
            return action_msg("修改成功！", 1);
        }else{
            return action_msg("修改失败！", 101);
        }
    }

    /**
     * Web_situation::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_web_situation::del($id);
    }

    /**
     * Web_situation::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $type){
        return Table_web_situation::getPageList($page, $pageSize, $count, $type);
    }
    static public function getPageListWeb($page, $pageSize, $count, $type){
        return Table_web_situation::getPageListWeb($page, $pageSize, $count, $type);
    }
    static public function getListQianDuan($params = array()){
        return Table_web_situation::getListQianDuan($params);
    }
    static public function getOtherList(){
        return Table_web_situation::getOtherList();
    }


}