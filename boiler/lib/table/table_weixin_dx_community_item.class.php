<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/5/25
 * Time: 11:14
 */

class Table_weixin_dx_community_item extends Table {

    private static  $pre = "item_";

    static protected function struct($data){
        $r = array();

        $r['id']                = $data['item_id'];
        $r['rule_id']           = $data['item_rule_id'];
        $r['community_id']         = $data['item_community_id'];


        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "rule_id"=>"number",
            "community_id"=>"number",

        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }
    static public function add($attrs){
        global $mypdo;

        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }
        return $mypdo->sqlinsert($mypdo->prefix.'dx_community_item', $params);
    }
    static public function getCommunityByRuleId($rule_id){

        global $mypdo;

        $rule_id = $mypdo->sql_check_input(array('number', $rule_id));

        $sql = "select * from ".$mypdo->prefix."dx_community_item where item_rule_id = $rule_id  ";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = $val['item_community_id'];
            }
            return $r;
        }else{
            return $r;
        }

    }

}