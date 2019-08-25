<?php
/**
 * Created by PhpStorm.
 * User: ChrisLin3
 * Date: 2019/8/13
 * Time: 16:27
 */
class Service_fee{
    public function __construct(){

    }

    public static function getList(){
        return Table_service_fee::getList();
    }

    public static function delete($id){
        return Table_service_fee::delete($id);
    }

    public static function add($number){
        $Table_service_fee = new Table_service_fee();
        $part_name = $Table_service_fee->getInfoByAccount($number);
        if($part_name) throw new MyException('该价格已经存在', 104);

        $attr = array(
            'number'   => $number,
            'status' => 1
        );
        Table_service_fee::add($attr);
    }

    public static function getInfoById($id){
        return Table_service_fee::getInfoById($id);
    }

    public static function update($id,$fee_number){
        return Table_service_fee::update($id,$fee_number);
    }
}
