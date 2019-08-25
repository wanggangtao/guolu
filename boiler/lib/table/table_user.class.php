<?php

/**
 * table_user.class.php 数据库表:用户表
 *
 * @version       v0.01
 * @createtime    2018/6/27
 * @updatetime    2018/6/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_user extends Table {


    private static  $pre = "user_";

    static protected function struct($data){
        $r = array();
        $r['id']          = $data['user_id'];
        $r['name']        = $data['user_name'];
        $r['account']     = $data['user_account'];
        $r['password']    = $data['user_password'];
        $r['salt']        = $data['user_salt'];
        $r['role']        = $data['user_role'];
        $r['parent']      = $data['user_parent'];
        $r['headimg']     = $data['user_headimg'];
        $r['birthday']    = $data['user_birthday'];
        $r['department']  = $data['user_department'];
        $r['status']      = $data['user_status'];
        $r['addtime']     = $data['user_addtime'];
        $r['lastupdate']  = $data['user_lastupdate'];
        $r['register_id']  = $data['user_register_id'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
            "account"=>"string",
            "password"=>"string",
            "salt"=>"string",
            "role"=>"number",
            "parent"=>"number",
            "headimg"=>"string",
            "birthday"=>"string",
            "department"=>"number",
            "status"=>"number",
            "addtime"=>"number",
            "lastupdate"=>"number",
            "register_id"=>"string",
        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }



    static public function add($attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }

        $r = $mypdo->sqlinsert($mypdo->prefix.'user', $params);
        return $r;
    }


    static public function update($id,$attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }
        //where条件
        $where = array(
            "user_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'user', $params, $where);
        return $r;
    }

    /**
     * Table_user::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."user where user_id = $id limit 1";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return $r;
        }
    }

    /**
     * Table_user::getInfoByAccount() 根据账号获取详情
     *
     * @param string $acount 账号
     *
     * @return
     */
    static public function getInfoByAccount($account){
        global $mypdo;

        $account = $mypdo->sql_check_input(array('string', $account));

        $sql = "select * from ".$mypdo->prefix."user where user_account = $account limit 1";

        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            $r = array();
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return 0;
        }
    }

    /**
     * Table_user::getInfoByParentid() 根据父类ID获取详情
     *
     * @param Integer $parentid  父类ID
     *
     * @return
     */
    static public function getInfoByParentid($parentid,$role){
        global $mypdo;

        $parentid = $mypdo->sql_check_input(array('number', $parentid));

        $sql = "select * from ".$mypdo->prefix."user where  user_status = 1 and user_parent = $parentid ";
        if (!empty($role)){
            $sql .=" and user_role=1 ";
        }
        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }


    /**
     * Table_user::getInfoByDepartment() 根据部门ID获取详情
     *
     * @param Integer $departmentid  部门ID
     *
     * @return
     */
    static public function getInfoByDepartment($departmentid){
        global $mypdo;

        $departmentid = $mypdo->sql_check_input(array('number', $departmentid));

        $sql = "select * from ".$mypdo->prefix."user where  user_status = 1 and user_department = $departmentid ";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }

    /**
     * Table_user::getInfoForParent() 根据角色获取详情
     *
     * @param Integer $departmentid  部门ID
     *
     * @return
     */
    static public function getInfoForParent($departmentid, $role){
        global $mypdo;

        $role = $mypdo->sql_check_input(array('number', $role));

        $sql = "select * from ".$mypdo->prefix."user where  user_status = 1 and user_role = $role+1 ";
        if($departmentid){
            $departmentid = $mypdo->sql_check_input(array('number', $departmentid));
            $sql.= " and user_department = $departmentid ";
        }
        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }

    /**
     * @param $id
     * @return array
     */
    static public function getChild($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."user where user_parent= $id";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }

    /**
     * Table_user::getInfoByRole() 根据角色ID获取详情
     *
     * @param Integer $roleid  角色ID
     *
     * @return
     */
    static public function getInfoByRole($roleid){
        global $mypdo;

        $roleid = $mypdo->sql_check_input(array('number', $roleid));

        $sql = "select * from ".$mypdo->prefix."user where user_status = 1 and user_role = $roleid ";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }

    /**
     * Table_user::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $name             姓名
     * @param number $status           状态
     * @param number $department       部门
     * @param number $role             角色
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $name, $status, $department, $role){
        global $mypdo;

        $where = " where 1=1 ";

        if($name){
            $name = $mypdo->sql_check_input(array('string', "%$name%"));
            $where .= " and user_name like $name ";
        }
        if($status){
            $status = $mypdo->sql_check_input(array('number', $status));
            $where .= " and user_status = $status ";
        }
        if($department){
            $department = $mypdo->sql_check_input(array('number', $department));
            $where .= " and user_department = $department ";
        }
        if($role){
            $role = $mypdo->sql_check_input(array('number', $role));
            $where .= " and user_role = $role ";
        }


        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."user".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."user".$where;
            $sql .=" order by user_department, user_id asc";

            $limit = "";
            if(!empty($page)){
                $start = ($page - 1)*$pageSize;
                $limit = " limit {$start},{$pageSize}";
            }

            $sql .= $limit;
            $rs = $mypdo->sqlQuery($sql);
            $r = array();
            if($rs){
                foreach($rs as $key => $val){
                    $r[$key] = self::struct($val);
                }
                return $r;
            }else{
                return $r;
            }
        }
    }
    
    /**
     * Table_user::del() 根据ID删除数据
     *
     * @param Integer $id  类别ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "user_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'user', $where);
    }

    /**
     * Table_user::delByParentid() 根据父类ID删除数据
     *
     * @param Integer $parentid  父类别ID
     *
     * @return
     */
    static public function delByParentid($parentid){

        global $mypdo;

        $where = array(
            "user_parent" => array('number', $parentid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'user', $where);
    }


    /**
     *
     * 根据部门id获取所有本部门的观察员
     * @param $id  部门id
     * @return array
     * @throws Exception
     */
    static public function getRoleIdByDepartment($id,$role){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select user_id from ".$mypdo->prefix."user where user_department= $id and user_role=$role and user_status=1";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = $val[0];
            }
            return $r;
        }else{
            return $r;
        }
    }

    static public function getIdByDepartment($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select user_id from ".$mypdo->prefix."user where user_department= $id and user_status=1 ";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = $val[0];
            }
            return $r;
        }else{
            return $r;
        }
    }



    static public function getUserIdByRole($role){
        global $mypdo;

        $role = $mypdo->sql_check_input(array('number', $role));

        $sql = "select user_id from ".$mypdo->prefix."user where user_role=$role";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = $val[0];
            }
            return $r;
        }else{
            return $r;
        }
    }

    static public function getRoleList(){
        global $mypdo;

        $sql = "select user_id from ".$mypdo->prefix."user";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = $val[0];
            }
            return $r;
        }else{
            return $r;
        }
    }

    static public function getRoleInfoByDepartment($id,$role){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."user where user_department= $id and user_role=$role and user_status=1";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }


    static public function getRole(){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."user where user_status = 1 and (user_role = 2 or user_role = 1) order by user_role desc";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }
}
?>