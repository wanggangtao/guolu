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

    /**
     * Repair_parts::getList() 获取零件信息
     * @return array
     */
    static public function getList(){
        return Table_repair_parts::getList();
    }

    /**
     * Repair_parts::part_num 修改零件数目
     * @param $name 所使用的零件名字
     * @param $num 使用零件数目
     * @return array|mixed
     */
    public static function part_num($name,$num){
        return Table_repair_parts::part_num($name,$num);
    }
    public static function getInfoById($id){
        return Table_repair_parts::getInfoById($id);
    }

    /**
     * Repair_part::add() 增加零件
     * zhh_fu
     * @param $name
     * @param $number
     * @param $price
     * @throws MyException
     */
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

    /**
     * update() 更新零件数目
     * @param $id
     * @param $part_num
     * @param $parts_unit_price
     * @return mixed
     */
    public static function update($id,$part_num,$parts_unit_price){
        return Table_repair_parts::update($id,$part_num,$parts_unit_price);
    }

    /**
     * delete() 假删，只是将状态变为-1
     * zhh_fu
     * @param $id
     */
    public static function delete($id){
        Table_repair_parts::delete($id);
    }
}
?>