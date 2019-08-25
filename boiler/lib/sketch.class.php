<?php
/**
 * Created by PhpStorm.
 * User: lp
 * Date: 2019/08/12
 * Time: 10:16
 */
class sketch
{
    /**
     * sketch::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_afterservice::add($attrs);
        return $id;
    }

    /**
     * sketch::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_afterservice::getInfoById($id);
    }

    /**
     * sketch::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        //  echo $id;
        $rs = Table_afterservice::update($id, $attrs);
        if($rs >= 0){
            return action_msg("修改成功！", 1);
        }else{
            return action_msg("修改失败！", 101);
        }
    }

    /**
     * sketch::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_afterservice::del($id);
    }

    /**
     * sketch::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count){
        return Table_afterservice::getPageList($page, $pageSize, $count);
    }

    /**
     * sketch::getInfoByType() 根据ID获取详情
     * @param $id
     * @return mixed
     */

    static public function getList(){
        return Table_afterservice::getList();
    }
}


