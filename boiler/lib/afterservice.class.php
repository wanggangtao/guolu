<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 2019/08/11
 * Time: 16:50
 */
class afterservice
{
    /**
     * afterservice::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  table_afterservice::add($attrs);

        return $id;
    }

    /**
     * afterservice::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return table_afterservice::getInfoById($id);
    }

    /**
     * afterservice::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        //  echo $id;
        $rs = table_afterservice::update($id, $attrs);
        if($rs >= 0){
            return action_msg("修改成功！", 1);
        }else{
            return action_msg("修改失败！", 101);
        }
    }

    /**
     * afterservice::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return table_afterservice::del($id);
    }

    /**
     * afterservice::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $sign){
        return table_afterservice::getPageList($page, $pageSize, $count,$sign);
    }

    /**
     * afterservice::getInfoByType() 根据ID获取详情
     * @param $id
     * @return mixed
     */

    static public function getList(){
        return table_afterservice::getList();
    }

    /**
     * afterservice::getInfoByType() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getListByType($id){
        return table_afterservice::getListByType($id);
    }
}

