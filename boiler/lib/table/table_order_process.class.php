<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/3/15
 * Time: 15:52
 */

class Table_order_process extends Table {

    private static  $pre = "order_";

    public static $OPERATION1 = 1;//客户下单预约
    public static $OPERATION2 = 2;//管理员已派单
    public static $OPERATION3 = 3;//维修师傅已接单
    public static $OPERATION4 = 4;//维修师傅维修完成
    public static $OPERATION5 = 5;//客户支付完成
    public static $OPERATION6 = 6;//管理员进行审核
    public static $OPERATION7 = 7;//客户取消订单
    public static $OPERATION8 = 8;//维修师傅申请重派
    public static $OPERATION9 = 9;//订单改派中
    public static $OPERATION10 = 10;//客服通过电话解决问题

    public static $ORDER_STATUS1 = 11;//正常待派单
    public static $ORDER_STATUS2 = 12;//重派单
    public static $ORDER_STATUS3 = 21;//待接单
    public static $ORDER_STATUS4 = 22;//已接单
    public static $ORDER_STATUS5 = 23;//待支付
    public static $ORDER_STATUS6 = 31;//已取消
    public static $ORDER_STATUS7 = 32;//待审核
    public static $ORDER_STATUS8 = 33;//已审核


    static protected function struct($data){
        $r = array();
        $r['id']         = $data['process_id'];
        $r['order_id']       = $data['process_order_id'];
        $r['addtime']       = $data['process_addtime'];
        $r['operation']   = $data['process_operation'];
        $r['order_status']   = $data['process_order_status'];
        $r['service_person']       = $data['process_service_person'];
        $r['person_phone']       = $data['process_person_phone'];
        $r['person_reason']    = $data['process_person_reason'];
                    return $r;
    }
    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "order_id"=>"number",
            "addtime"=>"number",
            "operation"=>"number",
            "order_status"=>"number",
            "service_person"=>"string",
            "person_phone"=>"string",
            "person_reason"=>"string",
        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    /***
     * @param $id
     * @param $content
     * @param $picture
     * @return mixed
     * 王刚涛
     * 添加重派原因
     */


    static public function reset_reason($attr)
    {
        global $mypdo;
//         var_dump($attr);
        $param = array (
            'process_order_id'   => array('number', $attr['order_id']),
            'process_addtime'  => array('number', $attr['addtime']),
            'process_operation'      => array('number', $attr['operation']),
            'process_order_status'      => array('number', $attr['order_status']),
            'process_person_phone'      => array('string', $attr['person_phone']),
            'process_person_reason'      => array('string', $attr['person_reason']),
            'process_service_person'      => array('string', $attr['service_person'])
        );
//        var_dump($param);
        return $mypdo->sqlinsert($mypdo->prefix .'order_process', $param);//返回插入记录的主键
    }
    /***
     * wanggangtao
     */
    static public function accept_orders($attr)
    {
        global $mypdo;
//         var_dump($attr);
        $param = array (
            'process_order_id'   => array('number', $attr['order_id']),
            'process_addtime'  => array('number', $attr['addtime']),
            'process_operation'      => array('number', $attr['operation']),
            'process_order_status'      => array('number', $attr['order_status']),
            'process_person_phone'      => array('string', $attr['person_phone']),
            'process_person_reason'      => array('string', $attr['person_reason']),
            'process_service_person'      => array('string', $attr['service_person'])
        );
//        var_dump($param);
        return $mypdo->sqlinsert($mypdo->prefix .'order_process', $param);//返回插入记录的主键
    }


    //增
    //@param $attr array -- 键值同struct()返回的数组
    //User: sxx
    public function add($attr){
        global $mypdo;
        $param = array (
            'process_order_id'   => array('number', $attr['order_id']),
            'process_addtime'  => array('number', $attr['addtime']),
            'process_operation'      => array('number', $attr['operation']),
            'process_order_status'      => array('number', $attr['order_status']),
            'process_person_phone'      => array('string', $attr['person_phone']),
            'process_person_reason'      => array('string', $attr['person_reason']),
            'process_service_person'      => array('string', $attr['service_person'])
        );


        return $mypdo->sqlinsert($mypdo->prefix .'order_process', $param);
    }


    /**
     * getInfoById() 根据ID获取详情
     * 
     * @param Integer   ID
     * 
     * User: sxx
     * 
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        
        $id = $mypdo->sql_check_input(array('number', $id));
        
        $sql = "select * from ".$mypdo->prefix."order_process where process_order_id = $id ";
        //echo $sql;
        
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
     * getCount() 获取当前订单流程记录条数
     * @param $id
     * @return int
     * @throws Exception
     * @author zhh_fu
     */
    static public function getCount($id){
        global $mypdo;
        $id = $mypdo->sql_check_input(array('number', $id));
        $sql = "select count(1) as num from ".$mypdo->prefix."order_process where process_order_id = $id ";

        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0]["num"];
        }else{
            return 0;
        }
    }


}
?>