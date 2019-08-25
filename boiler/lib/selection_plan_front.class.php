<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/6
 * Time: 19:51
 */
class Selection_plan_front {


    const WAIT_SOLVE = 0;
    const SOLVing= 1;
    const SOLVED= 2;


    public function __construct() {

    }

    /**
     * Selection_plan_front::add() 添加前端选型方案
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_selection_plan_front::add($attrs);

        return $id;
    }

    /**
     * 获取所有前端方案列表
     * @return mixed
     * @throws Exception
     */
    static public function getAllPlanList($params){

        return Table_selection_plan_front::getAllPlanList($params);
    }
    /**
     * 根据项目id获取对应的方案列表
     * @return mixed
     * @throws Exception
     */
    static public function getListByProid($params,$id){

        return Table_selection_plan_front::getListByProid($params,$id);
    }
    /**
     * 根据项目id获取列表长度
     * @param array $params
     * @return mixed
     */
    static public function getByProidCount($id){

        return Table_selection_plan_front::getByProidCount($id);
    }
    /**
     * 获取列表长度
     * @param array $params
     * @return mixed
     */
    static public function getAllCount($params = array()){

        return Table_selection_plan_front::getAllCount($params);
    }

    /**
     * Selection_plan_front::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_selection_plan_front::getInfoById($id);
    }


    static public function getInfoByHistoryId($id){
        return Table_selection_plan_front::getInfoByHistoryId($id);
    }

    static public function getUnSolve(){
        return Table_selection_plan_front::getUnSolve();
    }

    /**
     * Selection_plan_front::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_selection_plan_front::update($id, $attrs);
    }

    /**
     * Selection_plan_front::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_selection_plan_front::del($id);
    }

    static public function delByHistoryId($id){
        return Table_selection_plan_front::delByHistoryId($id);
    }

}
?>