<?php
/**
 * Created by PhpStorm.
 * User: sxx
 * Date: 2019/3/15
 * Time: 15:52
 */

class Table_repair_order extends Table {


    private static  $pre = "order_";

    //订单状态
    const status1 = 1;//订单待派单状态
    const status2 = 2;//订单待处理状态
    const status3 = 3;//订单已完工状态

    //客户端选择服务方式
    const solutions1 = 1;//上门服务
    const solutions2 = 2;//电话服务

    //客户满意度
    const client_satisfy1 = 1;//非常满意
    const client_satisfy2 = 2;//满意
    const client_satisfy3 = 3;//一般
    const client_satisfy4 = 4;//不满意
    const client_satisfy5 = 5;//非常不满意

    //订单子状态
    const child_status11 = 11;//正常待派单
    const child_status12 = 12;//重派单
    const child_status21 = 21;//待接单
    const child_status22 = 22;//已接单
    const child_status23 = 23;//待支付
    const child_status31 = 31;//已取消
    const child_status32 = 32;//待审核
    const child_status33 = 33;//已审核

    //支付方式
    const repair_pay_style1 = 1;//微信支付
    const repair_pay_style2 = 2;//现金支付

     //师傅端选择服务方式
    const master_solution1 = 1;//上门服务
    const master_solution2 = 2;//电话服务


    static protected function struct($data){
        $r = array();
        $r['id']         = $data['order_id'];
        $r['bar_code']       = $data['order_bar_code'];
        $r['phone']       = $data['order_phone'];
        $r['failure_cause']    = $data['order_failure_cause'];
        $r['picture_url']   = $data['order_picture_url'];
        $r['remarks']   = $data['order_remarks'];
        $r['status']       = $data['order_status'];
        $r['addtime']       = $data['order_addtime'];
        $r['content']    = $data['order_content'];
        $r['result']   = $data['order_result'];
        $r['person']   = $data['order_person'];
        $r['finish_time']       = $data['order_finish_time'];
        $r['linkphone']       = $data['order_linkphone'];
        $r['master_solution']       = $data['order_master_solution'];
        $r['register_person']       = $data['order_register_person'];
        $r['register_phone']       = $data['order_register_phone'];
        $r['address_all']       = $data['order_address_all'];
        $r['brand']       = $data['order_brand'];
        $r['model']       = $data['order_model'];
        $r['service_type']       = $data['order_service_type'];
        $r['coupon_id']       = $data['order_coupon_id'];
        $r['solutions']       = $data['order_solutions'];
        $r['status']       = $data['order_status'];
        $r['uid']       = $data['order_uid'];
        $r['user_evaluation']       = $data['order_user_evaluation'];
        $r['client_evaluation']       = $data['order_client_evaluation'];
        $r['client_satisfy']       = $data['order_client_satisfy'];
        $r['child_status']       = $data['order_child_status'];

        $r['user_evaluation']       = $data['order_user_evaluation'];
        $r['client_evaluation']       = $data['order_client_evaluation'];
        $r['client_satisfy']       = $data['Order_client_satisfy'];
        $r['client_valid']       = $data['order_client_valid'];
        $r['user_picture']       = $data['order_user_picture'];
        $r['repair_picture']       = $data['order_repair_picture'];
        $r['repair_hand_money']       = $data['order_repair_hand_money'];
        $r['repair_part_money']       = $data['order_repair_part_money'];
        $r['repair_pay_style']       = $data['order_repair_pay_style'];
        $r['sum_money']       = $data['order_sum_money'];
        $r['pay_num']       = $data['order_pay_num'];
        $r['treating_time']       = $data['order_treating_time'];
        $r['accept_time']       = $data['order_accept_time'];
        $r['verify_time']       = $data['order_verify_time'];

        return $r;
    }
    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "bar_code"=>"string",
            "phone"=>"string",
            "failure_cause"=>"string",
            "picture_url"=>"string",
            "remarks"=>"string",
            "status"=>"number",
            "person"=>"number",
            "finish_time"=>"number",
            "linkphone"=>"string",
            "addtime"=>"number",
            "content" => "string",
            "result" => "string",
            "register_person"=> "string",
            "register_phone"=> "string",
            "address_all"=> "string",
            "brand"=> "string",
            "model"=> "string",
            "service_type" => 'number',
            "coupon_id" => 'number',
            "solutions" => 'number',
            "user_evaluation" => "string",
            "client_evaluation" => "string",
            "client_satisfy" => "number",
            "child_status" => "number",
            "uid" => "number",
            "client_valid" => "string",
            "user_picture" => "string",
            "repair_picture" => "string",
            "repair_hand_money" => "number",
            "repair_part_money" => "number",
            "repair_pay_style" => "string",
            "sum_money" => "number",
            "pay_num" => "string",
            "treating_time"=>"number",
            "accept_time"=>"number",
            "verify_time"=>"number",
            "master_solution"=>"number",


        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    /***
     * @param $id
     * @param $status
     * @param $child_status
     * @return array
     * 王刚涛
     * 用户端的子状态函数
     */
    static public function getListrepair_order($id,$status ,$child_status){
    global $mypdo;
    $id = $mypdo->sql_check_input(array('number', $id));//项目的id
    $sql = "select * from ".$mypdo->prefix."repair_order where order_status != -1 and order_uid = {$id}  ";
    if($status!=0)
    {
        $sql .=" and order_status = {$status}";
    }
    if($child_status!=0)
    {
        $sql .=" and order_child_status = {$child_status}";
    }

    $sql .=" order by order_id desc";
//        echo $sql;
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

    /****
     * @param $id
     * @param $status
     * @param $child_status
     * @return array
     * 王刚涛
     * 师傅端的子状态的函数
     */
    static public function getList_repair_master($master_id,$status ,$child_status){
        global $mypdo;
        $id = $mypdo->sql_check_input(array('number', $master_id));//维修师傅的ID
        $sql = "select * from ".$mypdo->prefix."repair_order where order_status != -1 and order_person = {$master_id}  ";
        if($status!=0)
        {
            $sql .=" and order_status = {$status}";
        }
        if($child_status!=0)
        {
            $sql .=" and order_child_status = {$child_status}";
        }

        $sql .=" order by order_id desc";
//        echo $sql;
        $rs = $mypdo->sqlQuery($sql);
//        var_dump($rs);

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


    /**
     * @param $id
     * @return array
     * 王刚涛
     * 用户端获取某一项目的详细信息

     */
    static public function getrepair_detail($id ){
        global $mypdo;
        $id = $mypdo->sql_check_input(array('number', $id));
        $sql = "select * from ".$mypdo->prefix."repair_order where order_status != -1 and order_id = {$id}  ";
        $sql .=" order by order_id desc";
//        echo $sql;
        $rs = $mypdo->sqlQuery($sql);
//        var_dump($rs);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
//            var_dump($r);
            return $r[0];
        }else{
            return $r;
        }
    }

    /***
     * @param $id
     * @return mixed
     * 王刚涛
     * 师傅端接受订单，修改订单状态
     */
    static public function accept_order($id)
    {
        global $mypdo;
        $id = $mypdo->sql_check_input(array('number', $id));
        $param = array(
        'order_status'=>array('number', 2),//第一状态已接单
            'order_child_status'=>array('number', 22),//第二状态已接单

            'order_accept_time'=>array('number', time()),
    );
        $where = array(
            "order_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix .'repair_order', $param, $where);
//        var_dump($r);
        return $r;
    }

    /***
     * @param $id
     * @return mixed
     * 王刚涛
     * 申请重派状态修改
     */
    static public function reset_order($id)
    {
        global $mypdo;
        $id = $mypdo->sql_check_input(array('number', $id));
        $param = array(
            'order_status'=>array('number', 1),//第一状态已重派
            'order_child_status'=>array('number', 12),//第二状态已重派
        );
        $where = array(
            "order_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix .'repair_order', $param, $where);
        return $r;
    }




    static public function edit($id,$content,$picture)
    {
        global $mypdo;
        $param = array(
            'order_user_evaluation'=>array('string', $content),//用户评价啊
            'order_user_picture'=>array('string', $picture),//用户评价啊
        );
        $where = array(
            "order_id" => array('number', $id)
        );
        //返回结果
//        var_dump($mypdo->prefix .'repair_order');
        $r = $mypdo->sqlupdate($mypdo->prefix .'repair_order', $param, $where);
//        var_dump($r);
        return $r;
    }
    static public function master_submit($id,$resove_style, $content,$part_sum_money,$picture, $hand_money,$pay_style)
    {
        global $mypdo;
        $param = array(
            'order_master_solution'=>array('number', $resove_style),
            'order_content'=>array('string', $content),
            'order_repair_part_money'=>array('number', $part_sum_money),
            'order_repair_picture'=>array('string', $picture),
            'order_repair_hand_money'=>array('number', $hand_money),
            'order_repair_pay_style'=>array('number', $pay_style),
//            'order_remarks'=>array('string', $remark),
            'order_status'=>array('number',2 ),
            'order_child_status'=>array('number',23),
            'order_finish_time'=>array('number', time()),//当前的时间戳
        );
        $where = array(
            "order_id" => array('number', $id)
        );
        $r = $mypdo->sqlupdate($mypdo->prefix .'repair_order', $param, $where);
        return $r;
    }


  /***
     
     * @return mixed
     * sxx
     * 添加
     */
    static public function add($attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }

        $r = $mypdo->sqlinsert($mypdo->prefix.'repair_order', $params);
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
            "order_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'repair_order', $params, $where);
        return $r;
    }
    /**
     * Table_fact::getInfoById() 根据ID获取详情
     * user sxx
     * @param Integer $adminId  管理员ID
     *
     * @return
     */
    static public function getInfoById($factId){
        global $mypdo;

        $factId = $mypdo->sql_check_input(array('number', $factId));

        $sql = "select * from ".$mypdo->prefix."repair_order where order_status != -1  and order_id = $factId limit 1";
//echo $sql;
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




    static public function getList($params){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."repair_order where order_status != -1 ";

        if(!empty($params['value'])){
            $sql .=" and ( order_phone like '%".$params['value']."%' or order_register_person like '%".$params['value']."%' or 
            order_address_all like '%".$params['value']."%' ";
        }
        if(isset($params['rpName'])){
            $sql .= " or  order_person in (".$params['rpName'].") ";
        }
        if(isset($params['rpName']) or !empty($params['value'])){
            $sql .= " ) ";
        }

        if(!empty($params['starttime'])){
            $starttime = strtotime($params['starttime']);
            $endtime = strtotime($params['starttime'])+ 86399;
            $sql .=" and order_addtime > $starttime and  order_addtime < $endtime";
        }
        if(isset($params['status'])){
            $sql .=" and order_status =  ".$params['status'] ;
        }
        if(isset($params['child_status']) && !empty($params['child_status'])){
            $sql .=" and order_child_status =  ".$params['child_status'] ;
        }
        if(isset($params['PJ_status']) && !empty($params['PJ_status'])){
            if ($params['PJ_status']==1) {
            $sql .=" and order_user_evaluation = -1 " ;
            }else{
            $sql .=" and order_user_evaluation != -1 " ;    
            }
            
        }
        if(isset($params['unfinish'])){
            $sql .=" and order_status !=3" ;
        }
        if(isset($params['code'])){
            $sql .=" and order_bar_code = ".$params['code'] ;
        }

        if(isset($params['server_type']) && !empty($params['server_type'])){
            $sql .=" and order_service_type = ".$params['server_type'] ;
        }


        if(isset($params['solve_type']) && !empty($params['solve_type'])){
            $sql .=" and order_solutions = ".$params['solve_type'] ;
        }

        $sql .=" order by order_id desc";

        //echo $sql;
        $limit = "";
        if(!empty($params["page"]))
        {
            $start = ($params["page"]-1)*$params["pageSize"];

            $limit = " limit {$start},{$params["pageSize"]}";

        }

        $sql .=$limit;

//echo $sql;
        $rs = $mypdo->sqlQuery($sql);
        //print_r($rs);
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


    static public function getCount($params){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."repair_order where order_status != -1 ";
        if(!empty($params['value'])){
            $sql .=" and ( order_phone like '%".$params['value']."%' or 
            order_address_all like '%".$params['value']."%' ";
        }
        if(isset($params['rpName'])){
            $sql .= " or  order_person in (".$params['rpName'].") ";
        }
        if(isset($params['rpName']) or !empty($params['value'])){
            $sql .= " ) ";
        }
        if(!empty($params['starttime'])){
            $starttime = strtotime($params['starttime']);
            $endtime = strtotime($params['starttime'])+ 86399;
            $sql .=" and order_addtime > $starttime and  order_addtime < $endtime";
        }
        if(isset($params['status'])){
            $sql .=" and order_status =  ".$params['status'] ;
        }
        if(isset($params['code'])){
            $sql .=" and order_bar_code = ".$params['code'] ;
        }
        if(isset($params['server_type']) && !empty($params['server_type'])){
            $sql .=" and order_service_type = ".$params['server_type'] ;
        }

        if(isset($params['solve_type']) && !empty($params['solve_type'])){
            $sql .=" and order_solutions = ".$params['solve_type'] ;
        }

//        echo $sql;
        $rs = $mypdo->sqlQuery($sql);
        if($rs){

            return $rs[0]["act"];
        }else{
            return 0;
        }
    }




    /**
     * @param $factId
     * @return mixed
     */
    static public function del($factId){

        global $mypdo;
        $params = array('order_status'=>array('number',-1));

        $where = array(
            "order_id" => array('number', $factId)
        );

        $r = $mypdo->sqlupdate($mypdo->prefix.'repair_order', $params, $where);
        return $r;

    }

    /***
     * @param $id
     * @return mixed
     *
     * 王刚涛
     */
    static public function del_order($id)
    {

        global $mypdo;
        $params = array('order_status'=>array('number',-1));
        $where = array("order_id" => array('number', $id));//订单的编号是数字
        $r = $mypdo->sqlupdate($mypdo->prefix.'repair_order',$params, $where);
//        var_dump($r);
        return $r;
    }

    static public function getInfoByCode($code,$status){
        global $mypdo;
        $code = $mypdo->sql_check_input(array('string', $code));
        $sql = "SELECT * FROM boiler_repair_order WHERE order_bar_code = {$code}";
        if(!empty($status)){
            $sql .=" and order_status = ".$status." " ;
        }
        $sql .=" order by order_id desc";
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

    /**
     * @param $params
     * @return array
     * 维修记录
     */
    static public function getListByCode($params){
        global $mypdo;


       $sql="select * from boiler_repair_order WHERE 1=1  ";

        if(isset($params['status'])){
            $sql .=" and order_status =  ".$params['status'] ;
        }
        if(isset($params['unfinish'])){
            $sql .=" and order_status !=3 and order_status !=-1"  ;
        }
        if(isset($params['code'])){
            $sql .=" and order_bar_code = '".$params['code']."'" ;
        }
        //zx 添加group
        $sql .="  order by order_id desc";

        $limit = "";
        if(!empty($params["page"]))
        {
            $start = ($params["page"]-1)*$params["pageSize"];

            $limit = " limit {$start},{$params["pageSize"]}";

        }

        $sql .=$limit;

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

    /**
     * @param $params
     * @return array
     * 维修记录
     */
    static public function getCountByCode($params){
        global $mypdo;


        $sql="select count(1) as act from boiler_repair_order WHERE 1=1  ";

        if(isset($params['status'])){
            $sql .=" and order_status =  ".$params['status'] ;
        }

        if(isset($params['unfinish'])){
            $sql .=" and order_status != 3 " ;
        }

        if(isset($params['code'])){
            $sql .=" and order_bar_code = '".$params['code']."'" ;
        }
        if(isset($params['isDel'])){
            $sql .=" and order_status != -1 " ;
        }
        $sql .="  order by order_id desc";

        $rs = $mypdo->sqlQuery($sql);

        if($rs){
            return $rs[0]['act'];
        }else{
            return $rs;
        }
    }
    /***
     * 取消原因
     * 王刚涛
     */
    static public function valid_order($id,$reason)
    {
        global $mypdo;
        $date=date('Y-m-d H:i:s',time())."用户已经取消了订单";
        $id = $mypdo->sql_check_input(array('number', $id));
        $param = array(//用户端    取消订单，订单状态直接变为以完成
            'order_status'=>array('number', 3),//第一状态已重派
            'order_child_status'=>array('number', 31),//第二状态已重派
            'order_client_valid'=>array('string', $reason),//原因
            'order_content'=>array('string',$date ),//原因
        );
        $where = array(
            "order_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix .'repair_order', $param, $where);
        return $r;
    }

    static public function getInfoByOrderNum($orderNum){

        global $mypdo;
        $orderNum = $mypdo->sql_check_input(array('string', $orderNum));
        $sql = "select * from ".$mypdo->prefix."repair_order where order_status != -1 and order_pay_num = {$orderNum}  ";
        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return $r;
        }

    }

    static public function paySuccess($out_trade_no ,$total_fee){

        global $mypdo;

        $params = array(
            "order_status" => 3,
            "order_child_status" => 32,
            "order_repair_pay_style" => 1,
            "repair_sum_money" => $total_fee /100
        );

        //where条件
        $where = array(
            "order_pay_num" => array('string', $out_trade_no)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix . 'charge', $params, $where);
        return $r;

    }


}
?>