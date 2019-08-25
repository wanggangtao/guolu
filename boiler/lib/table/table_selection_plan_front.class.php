<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/6
 * Time: 19:59
 */
class Table_selection_plan_front extends Table {


    private static  $pre = "plan_front_";

    static protected function struct($data){
        $r = array();
        $r['id']                   = $data['plan_front_id'];
        $r['history_id']           = $data['plan_front_history_id'];
        $r['project_id']           = $data['plan_front_project_id'];
        $r['name']                  = $data['plan_front_name'];
        $r['tplcontent']            = $data['plan_front_tplcontent'];
        $r['addtime']         = floatval($data['plan_front_addtime']);
        $r['status']         = floatval($data['plan_front_status']);
        $r['url']         = $data['plan_front_url'];
        $r['pdf_url']         = $data['plan_front_pdf_url'];

        $r['customer_name']         = isset($data['customer_name'])?$data['customer_name']:"";

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "history_id"=>"number",
            "project_id"=>"number",
            "name"=>"string",
            "tplcontent"=>"string",
            "status"=>'number',
            "url"=>'string',
            "pdf_url"=>'string',

            "addtime"=>"number"
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'selection_plan_front', $params);
        return $r;
    }

    /**
     * 获取所有前端选型方案列表
     * @return array|mixed
     * @throws Exception
     */
    static public function getAllPlanList($params){
        global $mypdo;
        $sql = "select plan_front_id,
                        plan_front_history_id,
                        plan_front_project_id,
                        plan_front_name,
                        plan_front_tplcontent,
                        plan_front_addtime,
                        plan_front_status,
                        plan_front_url,
                        plan_front_pdf_url,
                        history_customer as customer_name
                        from ".$mypdo->prefix."selection_plan_front  
                        left join ".$mypdo->prefix."selection_history  
                        on ".$mypdo->prefix."selection_plan_front.plan_front_history_id = ".$mypdo->prefix."selection_history.history_id
                        where ".$mypdo->prefix."selection_plan_front.plan_front_status !=-1 and ".$mypdo->prefix."selection_plan_front.plan_front_status !=5 and ".$mypdo->prefix."selection_plan_front.plan_front_project_id =0
                        order by plan_front_id desc";
        $limit = "";

        if(!empty($params["page"]))
        {
            $start = ($params["page"]-1)*$params["pageSize"];
            $limit .= " limit {$start},{$params["pageSize"]}";
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

    /**
     * 根据项目id获取选型方案列表
     * @return array|mixed
     * @throws Exception
     */
    static public function getListByProid($params,$id){
        global $mypdo;
        $sql = "select plan_front_id,
                        plan_front_history_id,
                        plan_front_project_id,
                        plan_front_name,
                        plan_front_tplcontent,
                        plan_front_addtime,
                        plan_front_status,
                        plan_front_url,
                        plan_front_pdf_url,
                        history_customer as customer_name
                        from ".$mypdo->prefix."selection_plan_front  
                        left join ".$mypdo->prefix."selection_history  
                        on ".$mypdo->prefix."selection_plan_front.plan_front_history_id = ".$mypdo->prefix."selection_history.history_id
                        where ".$mypdo->prefix."selection_plan_front.plan_front_status !=-1 and ".$mypdo->prefix."selection_plan_front.plan_front_status !=5 and ".$mypdo->prefix."selection_plan_front.plan_front_project_id =$id
                        order by plan_front_id desc";
        $limit = "";

        if(!empty($params["page"]))
        {
            $start = ($params["page"]-1)*$params["pageSize"];
            $limit .= " limit {$start},{$params["pageSize"]}";
        }



        $sql .= $limit;

        $rs = $mypdo->sqlQuery($sql);
//        echo $sql;
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

    /**
     * 根据项目id获取列表长度
     * @param $params
     * @return array
     */
    static public function getByProidCount($id){
        global $mypdo;

        $sql="select count(1) as act from ".$mypdo->prefix."selection_plan_front  
                        left join ".$mypdo->prefix."selection_history  
                        on ".$mypdo->prefix."selection_plan_front.plan_front_history_id = ".$mypdo->prefix."selection_history.history_id
                        where ".$mypdo->prefix."selection_plan_front.plan_front_status !=-1 and ".$mypdo->prefix."selection_plan_front.plan_front_status !=5 and ".$mypdo->prefix."selection_plan_front.plan_front_project_id =$id";
        $rs=$mypdo->sqlQuery($sql);
        $r=array();
        if($rs){
            return $rs[0]["act"];
        }else{
            return $r;
        }

    }


    /**
     * 获取列表长度
     * @param $params
     * @return array
     */
    static public function getAllCount($params){
        global $mypdo;

        $sql="select count(1) as act from ".$mypdo->prefix."selection_plan_front  
                        left join ".$mypdo->prefix."selection_history  
                        on ".$mypdo->prefix."selection_plan_front.plan_front_history_id = ".$mypdo->prefix."selection_history.history_id
                        where ".$mypdo->prefix."selection_plan_front.plan_front_status !=-1 and ".$mypdo->prefix."selection_plan_front.plan_front_status !=5 and ".$mypdo->prefix."selection_plan_front.plan_front_project_id =0";
        $rs=$mypdo->sqlQuery($sql);
        $r=array();
        if($rs){
            return $rs[0]["act"];
        }else{
            return $r;
        }

    }



    /**
     * Table_selection_history::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."selection_plan_front where plan_front_id = $id limit 1";

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

    /**根据historyID获取
     * @param $id
     * @return array|mixed
     * @throws Exception
     */
    static public function getInfoByHistoryId($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."selection_plan_front where plan_front_history_id = $id";

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
     * 获取需要处理的文件
     * @return array|mixed
     */
    static public function getUnSolve(){
        global $mypdo;


        $sql = "select plan_front_id as id,plan_front_name as name from ".$mypdo->prefix."selection_plan_front where plan_front_status=0 limit 1";

        $rs = $mypdo->sqlQuery($sql);

        if($rs){

            return $rs[0];
        }else{
            return 0;
        }
    }

    /**
     * Table_selection_history::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "plan_front_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'selection_plan_front', $where);
    }

    static public function delByHistoryId($hid){

        global $mypdo;

        $where = array(
            "plan_front_history_id" => array('number', $hid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'selection_plan_front', $where);
    }

    /**
     * 更新
     * @param $id
     * @param $attrs
     * @return mixed
     * @throws Exception
     */
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
            "plan_front_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'selection_plan_front', $params, $where);
        return $r;
    }


    /**
     * Table_project::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $customer         客户名称
     * @param number $user             所属人
     * @param number $status           状态
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $customer, $user, $status){
        global $mypdo;

        $where = " where 1=1 ";
        if($customer){
            $customer = $mypdo->sql_check_input(array('string', "%".$customer."%"));
            $where .= " and history_customer like $customer ";
        }
        if($user){
            $user = $mypdo->sql_check_input(array('number', $user));
            $where .= " and history_user = $user ";
        }
        if($status > -1){
            $status = $mypdo->sql_check_input(array('number', $status));
            $where .= " and history_status = $status ";
        }

        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."selection_plan_front".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."selection_plan_front".$where;
            $sql .=" order by history_lastupdate desc";

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
}
?>