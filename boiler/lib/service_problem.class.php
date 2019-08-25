<?php
/**
 * Created by PhpStorm.
 * User: 张鑫
 * Date: 2019/3/20
 * Time: 10:58
 */

class Service_problem {

    public function __construct() {

    }

    /**
     * @param $attrs
     * @return mixed
     * @throws Exception
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_service_problem::add($attrs);
        $code = self::getCode($id);
        Table_service_problem::update($id, array("code"=>$code));
        return $id;
    }

    /**
     * @param $id
     * @return array|mixed
     *
     */
    static public function getInfoById($id){
        return Table_service_problem::getInfoById($id);
    }

    /**
     * Service_problem::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_service_problem::update($id, $attrs);
    }

    /**
     * Service_problem::del() 删除
     * @param $id
     * @return mixed
     */
    static public function dels($id){
        return Table_service_problem::dels($id);
    }


    /**
     * @param array $params
     * @return array
     */
    static public function getList($params=array()){
        return Table_service_problem::getList($params);
    }


    /**
     * @param array $params
     * @return null
     */
    static public function getCount($params=array()){
        return Table_service_problem::getCount($params);
    }

    /**
     * @param $id
     * @return string
     */
    static public function getCode($id){
        $var=sprintf("%05d", $id);
        return "F".$var;
    }

    static public function getBeginInfo(){
        return Table_service_problem::getBeginInfo();
    }
    static public function getInfoByCode($code){
        return Table_service_problem::getInfoByCode($code);
    }

    static public function getEndInfo(){
        return Table_service_problem::getEndInfo();
    }

    static public function getInfoByKeyword($keyword){
        return Table_service_problem::getInfoByKeyword($keyword);
    }

}