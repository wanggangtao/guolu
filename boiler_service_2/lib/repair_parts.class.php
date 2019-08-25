<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/12
 * Time: 16:21
 */
class Repair_parts {
    public function __construct(){

    }

    static public function getList(){
        return Table_repair_parts::getList();
    }

    public static function part_num($name,$num){
        return Table_repair_parts::part_num($name,$num);
    }
    public static function getInfoById($id){
        return Table_repair_parts::getInfoById($id);
    }

    public static function add($name,$number,$price){

        $Table_repair_parts = new Table_repair_parts();
        $part_name = $Table_repair_parts->getInfoByAccount($name);
        if($part_name) throw new MyException('该零件已经存在', 104);

        $attr = array(
            'name'  => $name,
            'number'   => $number,
            'unit_price' => $price,
            'status' => 1
        );
        Table_repair_parts::add($attr);
    }

    public static function update($id,$part_num,$parts_unit_price){
        return Table_repair_parts::update($id,$part_num,$parts_unit_price);
    }

    public static function delete($id){
        Table_repair_parts::delete($id);
    }
}
?>