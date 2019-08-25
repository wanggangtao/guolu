<?php

/**
 * admingroup.class.php 管理员分组类
 *
 * @version       v0.03
 * @create time   2014/09/04
 * @update time   2016/02/18 2016/3/25
 * @author        dxl wzp jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Factory {

    public  $fid    = 0;   //组ID
    public  $name   = '';  //组名
    public  $operator   = '';  //组权限，示例值：7001|7002|7003
    public  $time   = '';   //组类型，默认值：0-普通管理员组；9-超级管理员组

    /**
     * Admingroup::__construct()   构造函数
     *
     * @param integer $gid   管理员分组组ID
     *
     * @return
     */
    public function __construct($fid = 0) {
        if(empty($fid)) {
            throw new MyException('ID不能为空', 901);
        }else{
            $group = self::getInfoById($fid);
            if($group){
                $this->fid  = $fid;
                $this->name = $group['name'];
                $this->operator = $group['operator'];
                $this->time = $group['time'];
            }else{
                throw new MyException('工厂内容不存在', 902);
            }

        }
    }

    /**
     * Admingroup::add()   添加管理员组
     *
     * @param string  $groupname      组名
     * @param integer $grouptype      组类别值
     * @return
     */
    static public function add($groupname, $grouptype = 0){
        global $mypdo;

        if(empty($groupname)) throw new MyException('管理员组名不能为空', 101);

        //判断组名是否重复
        $g = Table_admingroup::getInfoByName($groupname);
        if($g) throw new MyException('组名已存在', 102);

        $gid = Table_admingroup::add($groupname, $grouptype);
        if($gid){
            $msg = '添加管理员组('.$gid.':'.$groupname.')成功!';
            Adminlog::add($msg);

            return action_msg($msg, 1);
        }else{
            throw new MyException('操作失败', 103);
        }
    }

    /**
     * Admingroup::edit() 修改管理员组
     *
     * @param integer $groupid       管理组ID
     * @param string  $groupname     管理组名
     * @param integer $grouptype     类型
     *
     * @return
     */
    static public function edit($groupid, $groupname, $grouptype = 0){

        if(empty($groupid)) throw new MyException('管理员组ID不能为空', 101);
        if(empty($groupname)) throw new MyException('管理员组名不能为空', 102);

        $rs = Table_admingroup::edit($groupid, $groupname, $grouptype);
        if($rs >= 0){//未做修改也算是修改成功

            $msg = '修改管理员组('.$groupid.')成功';
            Adminlog::add($msg);

            return action_msg($msg, 1);
        }else{
            if(empty($groupname)) throw new MyException('操作失败', 103);
        }
    }

    /**
     * Admingroup::del() 删除管理员组
     *
     * @param integer $gid   管理员组ID
     *
     * @return
     */
    static public function del($gid){
        if(empty($gid)) throw new MyException('管理员组ID不能为空', 101);

        //判断该组下是否有管理员
        if(self::getAdminCount($gid) > 0)  throw new MyException('该组有管理员。请先删除或转移管理员。', 102);

        $rs = Table_admingroup::del($gid);

        if($rs == 1){
            $msg = '删除管理员组('.$gid.')成功!';
            Adminlog::add($msg);

            return action_msg($msg, 1);//成功
        }else{
            throw new MyException('操作失败', 103);
        }
    }

    /**
     * Admingroup::getList() 获取管理员组列表
     *
     * @return
     */
    static public function getList(){
        return Table_factory::getList();
    }
    /**
     * Dict::getInfoByParentid() 根据父类ID获取详情
     * @param $parentid   父类别ID
     * @return mixed
     */
    static public function getInfoByParentid($parentid){
        return Table_factory::getInfoByParentid($parentid);
    }
    /**
     * Dict::getListByParentid() 根据父类ID获取列表
     * @param $parentid   父类ID
     * @return mixed
     */
    static public function getListByParentid($parentid){
        return Table_factory::getListByParentid($parentid);
    }

    /**
     * Admingroup::getInfoById() 管理员组详细信息
     *
     * @param integer $gid 管理组ID
     *
     * @return
     */
    static public function getInfoById($fid){
        if(empty($fid)) throw new MyException('ID不能为空', 101);

        return Table_factory::getInfoById($fid);
    }



    /**
     * Admingroup::getAdminCount() 管理员组所属管理员数量
     *
     * @param integer $gid   管理员组ID
     *
     * @return
     */
    static public function getAdminCount($gid = 0){

        return Table_admingroup::getAdminCount($gid);
    }





    public function getName(){
        return $this->name;
    }

}

?>