<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/12
 * 王刚涛
 * Time: 16:30
 */
class Table_repair_order_parts extends Table {

    private static  $pre = "parts_";
    public function __construct(){

    }
    static protected function struct($data){
        $r = array();
        $r['Info_id']                    = $data['repair_Info_id'];
        $r['order_id']                  = $data['repair_order_id'];
        $r['Info_part']                = $data['repair_Info_part'];
        $r['part_num']            = $data['repair_part_num'];
        $r['part_money']                = $data['repair_part_money'];

        return $r;
    }
    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "Info_id"=>"number",
            "order_id"=>"number",
            "Info_part"=>"string",
            "part_num"=>"number",
            "part_money"=>"number"
        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    /***
     * @param $id
     * @return array
     * 王刚涛
     * 找出维修订单所使用的相关零件
     */
    static public  function getrepair_part($id){
        global $mypdo;
        $id = $mypdo->sql_check_input(array('number', $id));
        $sql = "select * from ".$mypdo->prefix."repair_info where repair_order_id = $id";
//        print_r($sql);
        $rs = $mypdo->sqlQuery($sql);
//        print_r($rs);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
//                var_dump($r[$key]);
            }
            return $r;
        }else{
            return $r;
        }

    }

    static public function master_submit($id,$part,$part_num,$part_money)
    {
        global $mypdo;
        $param = array(
            "repair_order_id" => array('number', $id),
            'repair_Info_part'=>array('string', $part),
            'repair_part_num'=>array('number', $part_num),
            'repair_part_money'=>array('number', $part_money),
        );
        $r= $mypdo->sqlinsert($mypdo->prefix .'repair_info', $param);//返回插入记录的主键
        return $r;
    }



}
?>