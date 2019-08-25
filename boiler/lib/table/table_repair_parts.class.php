<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/12
 * Time: 16:30
 */
class Table_repair_parts extends Table {

    private static  $pre = "parts_";
    public function __construct(){

    }
    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['parts_id'];
        $r['name']                  = $data['parts_name'];
        $r['number']                = $data['parts_number'];
        $r['unit_price']            = $data['parts_unit_price'];
        $r['status']                = $data['parts_status'];

        return $r;
    }
    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
            "number"=>"number",
            "unit_price"=>"number",
            "status"=>"number"
        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }

    /***
     * @param $attr
     * wangangtao
     * 修改零件的总数
     *
     */
    public static function part_num($name,$num)
    {
        global $mypdo;
        $name = $mypdo->sql_check_input(array('string', $name));
        $sql = "select * from " . $mypdo->prefix . "repair_parts where parts_name = $name";
        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if ($rs) {
            foreach ($rs as $key => $val) {
                $r[$key] = self::struct($val);
            }
        }
        $name=$r[0]["name"];
        $new_num=$r[0]["number"]-$num;
        $params = array(
            "parts_number" => array('number', $new_num),
        );
        $where = array(
            "parts_name" => array('string', $name)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'repair_parts', $params, $where);
        return $r;

    }

    /**
     * add() 增加新零件
     * zhh_fu
     * @param $attr
     * @throws Exception
     */
    public static function add($attr){

        global $mypdo;
        $param = array();

        foreach ($attr as $key=>$value){
            $type = self::getTypeByAttr($key);
            $param[self::$pre.$key] =  array($type,$value);
        }

        $r = $mypdo->sqlinsert($mypdo->prefix.'repair_parts', $param);
    }

    /**
     * update() 更新零件数目
     * zhh_fu
     * @param $id
     * @param $part_num
     * @param $parts_unit_price
     * @return mixed
     * @throws Exception
     */
    public static function update($id,$part_num,$parts_unit_price){
        global $mypdo;
        $params = array(
            "parts_number" => array('number', $part_num),
            "parts_unit_price" => array('number',$parts_unit_price)
        );

        $where = array(
            "parts_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'repair_parts', $params, $where);
        return $r;
        //注释语句不合适
        //$sql = "update boiler_repair_parts set parts_number = ".$part_num.",parts_unit_price = ".$parts_unit_price." WHERE parts_id = {$id}";
        //$num = $mypdo->exec($sql);
    }

    /**
     * @return array
     * 获取零件信息
     */
    public static function getList(){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."repair_parts";
        $where=" where parts_status =1 ";
        $sql.=$where;
        $sql.=" order by parts_id asc";

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

    public static function getInfoById($id){
        global $mypdo;
        $id = $mypdo->sql_check_input(array('number', $id));
        $sql = "select * from ".$mypdo->prefix."repair_parts where parts_id = $id limit 1";

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
     * getInfoByAccount() 通过名字获取信息
     * zhh_fu
     * @param $name
     * @return array|mixed
     * @throws Exception
     */
    public function getInfoByAccount($name){
        global $mypdo;
        $name = $mypdo->sql_check_input(array('string', $name));

        $sql = "select * from ".$mypdo->prefix."repair_parts where parts_name = $name limit 1";

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

    public static function delete($id){
        global $mypdo;
        //假删，只是状态发生改变，在数据库并未消失
        $params = array(
            "parts_status" => array('number', -1)
        );

        $where = array(
            "parts_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'repair_parts', $params, $where);
        return $r;
    }


}
?>