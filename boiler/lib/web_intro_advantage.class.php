<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2018/12/27 0027
 * Time: 下午 2:07
 */
class web_intro_advantage
{
    /**
     * Web_introduction::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_web_intro_advantage::add($attrs);
        return $id;
    }

    /**
     * Web_introduction::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return table_web_intro_advantage::getInfoById($id);
    }

    /**
     * Web_introduction::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
      //  echo $id;
        $rs = table_web_intro_advantage::update($id, $attrs);
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
        return Table_web_intro_advantage::del($id);
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
        return table_web_intro_advantage::getPageList($page, $pageSize, $count);
    }

    /**
     * Web_introduction::getInfoByType() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getListByType($type){
        return table_web_intro_advantage::getListByType($type);
    }

}