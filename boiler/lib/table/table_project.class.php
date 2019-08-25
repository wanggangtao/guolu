<?php

/**
 * table_project.class.php 数据库表:项目表
 *
 * @version       v0.01
 * @createtime    2018/6/24
 * @updatetime    2018/6/24
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project extends Table {


    private static  $pre = "project_";

    static protected function struct($data){
        $r = array();
        $r['id']            = $data['project_id'];
        $r['code']          = $data['project_code'];
        $r['name']          = $data['project_name'];
        $r['addtime']       = $data['project_addtime'];
        $r['level']         = $data['project_level'];
        $r['status']        = $data['project_status'];
        $r['one_status']    = $data['project_one_status'];
        $r['two_status']    = $data['project_two_status'];
        $r['three_status']  = $data['project_three_status'];
        $r['four_status']   = $data['project_four_status'];
        $r['type']          = $data['project_type'];
        $r['lastupdate']    = $data['project_lastupdate'];
        $r['user']          = $data['project_user'];
        $r['stop_flag']     = $data['project_stop_flag'];
        $r['stopreason']    = $data['project_stopreason'];
        $r['summarize']     = $data['project_summarize'];
        $r['detail']        = $data['project_detail'];
        $r['reviewopinion'] = $data['project_reviewopinion'];
        $r['bonus']         = $data['project_bonus'];
        $r['notice_one']    = $data['project_notice_one'];
        $r['notice_two']    = $data['project_notice_two'];
        $r['notice_three']  = $data['project_notice_three'];
        $r['export_flag']   = $data['project_export_flag'];
        $r['bonus_stage']   = $data['project_bonus_stage'];
        $r['del_flag']      = $data['project_del_flag'];
        $r['del_reason']    = $data['project_del_reason'];
        $r['notsame_id']    = $data['project_notsame_id'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "code"=>"string",
            "name"=>"string",
            "addtime"=>"number",
            "level"=>"number",
            "type"=>"number",
            "status"=>"number",
            "one_status"=>"number",
            "two_status"=>"number",
            "three_status"=>"number",
            "four_status"=>"number",
            "lastupdate"=>"number",
            "user"=>"number",
            "stop_flag"=>"number",
            "stopreason"=>"string",
            "summarize"=>"string",
            "detail"=>"string",
            "reviewopinion"=>"string",
            "bonus"=>"number",
            "notice_one"=>"number",
            "notice_two"=>"number",
            "notice_three"=>"number",
            "export_flag"=>"number",
            "bonus_stage"=>"string",
            "del_flag"=>"number",
            "del_reason"=>"string",
            "notsame_id"=>"string"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'project', $params);
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
            "project_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project', $params, $where);
        return $r;
    }

    /**
     * Table_project::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project where project_id = $id limit 1";

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
     * Table_project::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $name             名称
     * @param number $type             类型
     * @param number $status           名称
     * @param number $level            阶段
     * @param number $user             项目所属人
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $name, $type, $status, $level, $user,$startTime,$endTime){
        global $mypdo;

        $where = " where project_del_flag = 0 ";
        if($name){

            //处理多词搜索

            $nameArr = explodeNew($name);

            if(!empty($nameArr))
            {
                foreach ($nameArr as $itemName)
                {
                    $itemName = $mypdo->sql_check_input(array('string', "%".$itemName."%"));
                    $where .= " and project_name like $itemName ";
                }
            }

        }
        if($type){
            $type = $mypdo->sql_check_input(array('number', $type));
            $where .= " and project_type = $type ";
        }
        if($status > -1){
            $status = $mypdo->sql_check_input(array('number', $status));
            $where .= " and project_status = $status ";
        }
        if($level > -1){
            $level = $mypdo->sql_check_input(array('number', $level));
            if($level == 11){
                $where .= " and project_stop_flag = 1  and project_status = 3 ";
            }else{
                $where .= " and project_level = $level ";
            }
        }
        if($user){
            $user = $mypdo->sql_check_input(array('number', $user));
            $where .= " and project_user = $user ";
        }

        if(!empty($startTime))
        {
            $where .= " and project_addtime >= $startTime ";
        }

        if(!empty($endTime))
        {
            $where .= " and project_addtime <= $endTime";
        }

        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."project".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."project".$where;
            $sql .=" order by project_lastupdate desc";

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
            }

            return $r;
        }
    }

    /**
     * Table_project::getPageReviewList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $name             名称
     * @param number $type             类型
     * @param number $status           名称
     * @param number $level            阶段
     * @param string $user             项目所属人
     * @return
     */
    static public function getPageReviewList($page, $pageSize, $count, $name, $type, $status, $level, $user, $username, $startTime, $endTime){
        global $mypdo;

        $where = " where  project_del_flag = 0  ";
        if($name){

            //处理多词搜索

            $nameArr = explodeNew($name);

            if(!empty($nameArr))
            {
                foreach ($nameArr as $itemName)
                {
                    $itemName = $mypdo->sql_check_input(array('string', "%".$itemName."%"));
                    $where .= " and project_name like $itemName ";
                }
            }

        }
        if($type){
            $type = $mypdo->sql_check_input(array('number', $type));
            $where .= " and project_type = $type ";
        }
        if($status > -1){
            $status = $mypdo->sql_check_input(array('number', $status));
            $where .= " and project_status = $status ";
        }else{
            $where .= " and project_status > 1 ";
        }
        if($level > -1){
            $level = $mypdo->sql_check_input(array('number', $level));
            if($level == 11){
                $where .= " and project_stop_flag = 1  and project_status = 3 ";
            }else{
                $where .= " and project_level = $level ";
            }
        }
        if($user){
            $user = $mypdo->sql_check_input(array('number', $user));
            $where .= " and (project_user in (select user_id from boiler_user where user_parent = $user ) or project_user = $user)";
        }

        if($username){
            $username = $mypdo->sql_check_input(array('string', "%".$username."%"));
            $where .= " and project_user in (select user_id from boiler_user where user_name like $username ) ";
        }
        if(!empty($startTime))
        {
            $where .= " and project_addtime >= $startTime ";
        }

        if(!empty($endTime))
        {
            $where .= " and project_addtime <= $endTime";
        }
        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."project".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."project".$where;
            $sql .=" order by project_lastupdate desc";

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
            }

            return $r;
        }
    }

    /**
     * Table_project::getPageSeclectList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $name             名称
     * @param number $type             类型
     * @param number $status           名称
     * @param number $level            阶段
     * @param string $user             项目所属人
     * @return
     */
    static public function getPageSeclectList($page, $pageSize, $count, $name, $type, $status, $level, $user, $loginuser, $startTime, $endTime, $department){
        global $mypdo;

        $where = " where  project_del_flag = 0  ";
        if($name){

            //处理多词搜索

            $nameArr = explodeNew($name);

            if(!empty($nameArr))
            {
                foreach ($nameArr as $itemName)
                {
                    $itemName = $mypdo->sql_check_input(array('string', "%".$itemName."%"));
                    $where .= " and project_name like $itemName ";
                }
            }

        }
        if($type){
            $type = $mypdo->sql_check_input(array('number', $type));
            $where .= " and project_type = $type ";
        }
        if($status > -1){
            $status = $mypdo->sql_check_input(array('number', $status));
            $where .= " and project_status = $status ";
        }else{
            $where .= " and project_status > 1 ";
        }
        if($level > -1){
            $level = $mypdo->sql_check_input(array('number', $level));
            if($level == 11){
                $where .= " and project_stop_flag = 1 and project_status = 3 ";
            }else{
                $where .= " and project_level = $level ";
            }
        }
        if($user){
            $user = $mypdo->sql_check_input(array('string', "%".$user."%"));
            $where .= " and project_user in (select user_id from boiler_user where user_name like $user ) ";
        }

        if($loginuser){
            $loginuser = $mypdo->sql_check_input(array('number', $loginuser));
            $where .= " and (project_user in (select user_id from boiler_user where user_parent = $loginuser ) or project_user = $loginuser)";
        }

        if($department){
            $department = $mypdo->sql_check_input(array('number', $department));
            $where .= " and (project_user in (select user_id from boiler_user where user_department = $department ))";
        }

        if(!empty($startTime))
        {
            $where .= " and project_addtime >= $startTime ";
        }

        if(!empty($endTime))
        {
            $where .= " and project_addtime <= $endTime";
        }
        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."project".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."project".$where;
            $sql .=" order by project_id desc";
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
            }

            return $r;
        }
    }

    /**
     * Table_project::getAllSendList() 根据条件列出所有信息
     * @return
     */
    static public function getAllSendList(){
        global $mypdo;

        $where = " where  project_del_flag = 0  and project_level >= 1 and project_stop_flag = 0  ";

        $sql = "select * from ".$mypdo->prefix."project".$where;
        $sql .=" order by project_id desc";

        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }

        return $r;
    }

    /**
     * Table_project::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "project_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project', $where);
    }

    /**
     * Table_project::getInfoByName() 根据项目名获取详情
     *
     * @param string $name  项目名
     *
     * @return
     */
    static public function getInfoByName($name){
        global $mypdo;

        $name = $mypdo->sql_check_input(array('string', $name));

        $sql = "select * from ".$mypdo->prefix."project where project_name = $name and  project_del_flag = 0 ";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }


    /****************************************************app接口*************************************************/
    /**
     * 获取总数量
     * @param $userId
     * @return int
     */
    static public function getTotalCount($userId)
    {
        global $mypdo;
        $userId = $mypdo->sql_check_input(array('number', $userId));
        $sql = "select count(1) as act from ".$mypdo->prefix."project where project_user={$userId} and project_del_flag = 0 ";
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0]["act"];
        }
        else
        {
            return 0;
        }
    }





    /**
     * 获取未报备数量
     * @param $userId
     * @return int
     */
    static public function getNotReportCount($userId)
    {
        global $mypdo;
        $userId = $mypdo->sql_check_input(array('number', $userId));
        $sql = "select count(1) as act from ".$mypdo->prefix."project where  project_del_flag = 0 and project_user={$userId} and project_level = ".project::UN_REPORT." and project_stop_flag=".project::DOING;
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0]["act"];
        }
        else
        {
            return 0;
        }
    }



    /**
     * 获取进行中的数量
     * @param $userId
     * @return int
     */
    static public function getDoingCount($userId)
    {
        global $mypdo;

        $userId = $mypdo->sql_check_input(array('number', $userId));

        $sql = "select count(1) as act from ".$mypdo->prefix."project where  project_del_flag = 0 and project_user={$userId} and project_level >".project::UN_REPORT." and project_stop_flag=".project::DOING;

        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0]["act"];
        }
        else
        {
            return 0;
        }
    }



    /**
     * 获取已终止的数量
     * @param $userId
     * @return int
     */
    static public function getStopCount($userId)
    {
        global $mypdo;

        $userId = $mypdo->sql_check_input(array('number', $userId));

        $sql = "select count(1) as act from ".$mypdo->prefix."project where  project_del_flag = 0 and project_user={$userId}  and project_stop_flag=".project::STOP;

        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0]["act"];
        }
        else
        {
            return 0;
        }
    }


    static public function getReviewCount($userId,$role)
    {
        global $mypdo;
        $userId = $mypdo->sql_check_input(array('number', $userId));
        if ($role==2){
            $sql = "select count(1) as act from ".$mypdo->prefix."project where  project_del_flag = 0 and project_status=2 and project_user = $userId";

        }else{
            $sql = "select count(1) as act from ".$mypdo->prefix."project where  project_del_flag = 0 and project_status=2 and project_user = {$userId}";

        }
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0]["act"];
        }
        else
        {
            return 0;
        }
    }

    static public function getReviewProjectCount($userId,$role)
    {
        global $mypdo;
        $userId = $mypdo->sql_check_input(array('number', $userId));
        if ($role==2){
            $sql = "select count(1) as act from ".$mypdo->prefix."project where  project_del_flag = 0 and project_status=2 and (project_user in (select user_id from boiler_user where user_parent = $userId ) or project_user = $userId)";

        }else{
            $sql = "select count(1) as act from ".$mypdo->prefix."project where  project_del_flag = 0 and project_status=2";

        }
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0]["act"];
        }
        else
        {
            return 0;
        }
    }

}
?>