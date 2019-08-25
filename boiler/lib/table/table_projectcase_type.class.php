
<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/20
 * Time: 10:22
 */
class Table_projectcase_type extends Table {


    private static  $pre = "projectcase_type_";

    static protected function struct($data){
        $r = array();
        $r['id']           = $data['projectcase_type_id'];
        $r['name']        = $data['projectcase_type_name'];
        $r['english_name']        = $data['projectcase_type_english_name'];
        $r['order']        = $data['projectcase_type_order'];
        $r['operator']      = floatval($data['projectcase_type_operator']);
        $r['addtime']      = floatval($data['projectcase_type_addtime']);

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
            "english_name"=>"string",
            "order"=>"number",
            "operator"=>"number",
            "addtime"=>"number"
        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }
    static public function getList(){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."projectcase_type order by projectcase_type_order asc, projectcase_type_id desc";

        $rs  = $mypdo->sqlQuery($sql);
        $r   = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }

    static public function getCount(){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."projectcase_type";
        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){

            return $rs[0]["act"];
        }else{
            return 0;
        }
    }

    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."projectcase_type where projectcase_type_id = $id limit 1";

        $rs = $mypdo->sqlQuery($sql);

        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }



    static public function getPageList($page,$pageSize){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."projectcase_type";

        $where = " where 1=1 ";

        $sql .= $where;
        $sql .=" order by projectcase_type_order desc";

        $limit = "";
        if(!empty($page))
        {

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



    static public function add($attrs){
        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }

        $r = $mypdo->sqlinsert($mypdo->prefix.'projectcase_type', $params);
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
            "projectcase_type_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'projectcase_type', $params, $where);
        return $r;
    }

    static public function del($id){

        global $mypdo;

        $where = array(
            "projectcase_type_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'projectcase_type', $where);
    }
    static public function getListByOrder($page, $pageSize, $count){
        global $mypdo;

        $where = " where 1 = 1 ";

        $where .= " order by projectcase_type_order asc";

        if(1 == $count){
            $sql = "select count(*) as ct from ".$mypdo->prefix."projectcase_type".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }

        $sql = "select * from ".$mypdo->prefix."projectcase_type".$where;
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