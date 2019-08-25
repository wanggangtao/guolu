<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/15
 * Time: 15:25
 */

class Repair_person {

    public function __construct() {

    }

    /***
     * @param $phone
     * @return array|mixed
     * wanggangtao
     * 根据用户的手机号码，获取用户的相关信息
     */
    static public function getInfoByPhone($phone){
        return Table_repair_person::getInfoByPhone($phone);
    }

    /***
     * @param $openId
     * @return array|mixed
     * openid
     */
    static public function getInfoByOpenid($openId){
        return Table_repair_person::getInfoByOpenid($openId);

    }


    static public function login_out($openId){

        return Table_repair_person::login_out($openId);
    }

    /**
     * Category::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_repair_person::update($id, $attrs);
    }


    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_repair_person::add($attrs);

        return $id;
    }

    /**
     * Category::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_repair_person::getInfoById($id);
    }
    /**
     * Category::del() 删除
     * @param $id
     * @return mixed
     */
    static public function dels($id){
        return Table_repair_person::dels($id);
    }



    static public function getList($params=array()){
        return Table_repair_person::getList($params);
    }

    static public function getCount($params=array()){
        return Table_repair_person::getCount($params);
    }

    static public function getIdByLikeName($name){
        return Table_repair_person::getIdByLikeName($name);
    }

    static public function getPhoneByLikeName($name){
        return Table_repair_person::getPhoneByLikeName($name);
    }

    }