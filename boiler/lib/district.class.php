<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2017/7/15
 * Time: 9:48
 */
class district
{
    const PROVINCE = 3;

    static function getInfoById($id)
    {
        if(empty($id)) throw new MyException("id不能为空!",301);
        return Table_district::getInfoById($id);
    }

    static function getInfoByType($type=self::PROVINCE)
    {
        return Table_district::getInfoByType($type=self::PROVINCE);
    }

    static function getInfoByUpid($upid)
    {

        return Table_district::getInfoByUpid($upid);
    }


    static function getInfoLikeName($name,$type)
    {
        if(empty($name)) throw new MyException("name不能为空!",302);
        return Table_district::getInfoLikeName($name,$type);
    }



    /**
     获取子地址
     */
    static public function getAddressType($type,$upid = "" ){

        $rs = Table_district::getAddressType($type,$upid);
        if ($rs >= 0) {
            return $rs;
        } else {
            throw new MyException('操作失败', 106);
        }
    }

    static public function getAddressNameById($id){

        $rs = Table_district::getAddressNameById($id);
        if ($rs >= 0) {
            return $rs;
        } else {
            throw new MyException('操作失败', 106);
        }
    }
    static public function getAddressInfoByName($name,$upid){
        if(empty($name)) throw new MyException("name不能为空!",302);
        return Table_district::getAddressInfoByName($name,$upid);
    }

    }