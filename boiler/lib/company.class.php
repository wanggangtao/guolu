<?php

/**
 * company.class.php 企业内容类
 *
 * @version       v0.03
 * @create time   2014/09/04
 * @update time   2016/02/18 2016/3/25
 * @author        dxl wzp jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Company {

    public  $cid    = 0;   //ID
    public  $name   = '';  //名称
    public  $operator   = '';  //操作人
    public  $time   = '';   //操作时间

    /**
     * Admingroup::__construct()   构造函数
     *
     * @param integer $gid   管理员分组组ID
     *
     * @return
     */
    public function __construct($cid = 0) {
        if(empty($cid)) {
            throw new MyException('企业ID不能为空', 901);
        }else{
            $company = self::getInfoById($cid);
            if($company){
                $this->cid  = $cid;
                $this->name = $company['name'];
                $this->operator = $company['operator'];
                $this->time = $company['time'];
            }else{
                throw new MyException('企业内容不存在', 902);
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
    static public function add($name){
        global $mypdo;

        if(empty($name)) throw new MyException('企业名称不能为空', 101);

        //判断组名是否重复
        $g = Table_company::getInfoByName($name);
        if($g) throw new MyException('企业名称已存在', 102);

        $gid = Table_company::add($name);
        if($gid){
            $msg = '添加成功!';

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
    static public function edit($id, $name, $abstract,$intelligence,$logo){

        if(empty($id)) throw new MyException('企业ID不能为空', 101);
        if(empty($name)) throw new MyException('企业名称不能为空', 102);
        if(empty($abstract)) throw new MyException('企业简介不能为空', 103);
        if(empty($intelligence)) throw new MyException('企业资质不能为空', 104);
        if(empty($logo)) throw new MyException('企业logo不能为空', 105);


        $rs = Table_company::edit($id, $name, $abstract,$intelligence,$logo);
        if($rs >= 0){//未做修改也算是修改成功

            $msg = '修改成功';

            return action_msg($msg, 1);
        }else{
            if(empty($name)) throw new MyException('操作失败', 103);
        }
    }

    /**
     * Admingroup::del() 删除管理员组
     *
     * @param integer $gid   管理员组ID
     *
     * @return
     */
    static public function del($cid){
        if(empty($cid)) throw new MyException('企业ID不能为空', 101);


        $rs = Table_company::del($cid);

        if($rs == 1){
            $msg = '删除成功!';

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
        return Table_company::getList();
    }
    /**
     * Dict::getInfoByParentid() 根据父类ID获取详情
     * @param $parentid   父类别ID
     * @return mixed
     */
    static public function getInfoByParentid($parentid){
        return Table_company::getInfoByParentid($parentid);
    }
    /**
     * Dict::getListByParentid() 根据父类ID获取列表
     * @param $parentid   父类ID
     * @return mixed
     */
    static public function getListByParentid($parentid){
        return Table_company::getListByParentid($parentid);
    }

    /**
     * Admingroup::getInfoById() 管理员组详细信息
     *
     * @param integer $gid 管理组ID
     *
     * @return
     */
    static public function getInfoById($ID){
        if(empty($ID)) throw new MyException('ID不能为空', 101);

        return Table_company::getInfoById($ID);
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


    public function getAuth(){
        return $this->auth;
    }
    public function getName(){
        return $this->name;
    }

}

?>