<?php
/**
 * Created by PhpStorm.
 * User: tangqiang
 * Date: 17/7/8
 * Time: 上午11:12
 */


class version{


/****status****/

    const  ONLINE = 1;//上线
    const  DOWNLINE =2;//下线



    static public function getAvailVersion($appType)
    {
        return Table_version::getAvailVersion($appType);
    }


    static public function add($attrs)
    {
        /**
         * 加一些处理
         */
        $versionNum = implode("",explode(".",$attrs["name"]));

        if(!is_numeric($versionNum)) throw new MyException("版本号不符合规范",101);
        self::disableAll($attrs["app_type"]);

        $attrs["code"] = self::getVersionCode($attrs["name"]);

        return Table_version::add($attrs);

    }

    static public function disableAll($app_type)
    {
        /**
         * 加一些处理
         */

        return Table_version::disableAll($app_type);

    }


    static public function update($id,$attrs)
    {
        /**
         * 加一些处理
         */
        $versionNum = implode("",explode(".",$attrs["name"]));

        if(!is_numeric($versionNum)) throw new MyException("版本号不符合规范",101);
        $attrs["code"] = self::getVersionCode($attrs["name"]);
        return Table_version::update($id,$attrs);

    }


    static public function getList($status=0)
    {
        return Table_version::getList($status);
    }

    static public function getPageList($page,$pageSize)
    {
        return Table_version::getPageList($page,$pageSize);
    }

    static public function getCount($status=0)
    {
        return Table_version::getCount($status);
    }


    static function getVersionCode($version)
    {

//        $versionCode = Table_version::getMaxVersion($userType,$appType);
//
//        return empty($versionCode)?100:(++$versionCode);


        $versionNum = implode("",explode(".",$version));

        if(!is_numeric($versionNum)) throw new MyException("版本号不符合规范",101);

        return $versionNum;

    }


    static function getVersionByCode($versionCode)
    {

        $versionArr = str_split($versionCode);

        return implode(".",$versionArr);

    }

    static function getInfoById($id)
    {

        return Table_version::getInfoById($id);

    }

    static public function del($id){

        if(empty($id))throw new MyException('id不能为空', 101);

        $rs = Table_version::del($id);
        if($rs == 1){
            return $rs;
        }else{
            throw new MyException('操作失败', 102);
        }
    }

}
