<?php

/**
 * service_type.class.php  优惠券类
 *
 * @version       v0.03
 * @create time   2014/09/04
 * @update time   2016/02/18 2016/3/25
 * @author        dxl wzp jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Weixin_coupon {


    public function __construct()
    {

    }

    static public function add($attrs)
    {
        if (empty($attrs)) {
            throw new Exception('参数不能为空', 102);
        }
        $cname = Table_weixin_coupon::getInfoByName($attrs['name']);
        if($cname) throw new MyException('优惠券名已存在', 102);
        $id = Table_weixin_coupon::add($attrs);

        return $id;
    }

    static public function getInfoById($id)
    {
        return Table_weixin_coupon::getInfoById($id);
    }

    static public function update($id, $attrs)
    {
        return Table_weixin_coupon::update($id, $attrs);
    }

    static public function dels($id)
    {
        $nums=Weixin_coupon_register_rule::getInfoByTime(time());
        $dx_nums = Weixin_dx_coupon_item::getInfoByTime($id);

        $dx_flag = false;
        if(!empty($dx_nums)){
            $dx_flag = true;
        }

        $flag = false;
        foreach ($nums as $num){
            if($num['item_coupon_id']==$id){
                $flag = true;
                break;
            }
        }
//        print_r($flag);
//        exit();
         if($flag || $dx_flag) {
             return -2;
         }else{
             return Table_weixin_coupon::dels($id);
         }


    }

    static public function getList($params = array())
    {
        return Table_weixin_coupon::getList($params);
    }

    static public function getCount($params = array())
    {
        return Table_weixin_coupon::getCount($params);
    }

    static public function getNameById($id)
    {
        $row = self::getInfoById($id);

        return isset($row['name']) ? $row['name'] : "";
    }

    static public function getInfoByName($name)
    {
        return Table_weixin_coupon::getInfoByName($name);
    }
    static public function getInfoByIdAndType($id,$type){
        return Table_weixin_coupon::getInfoByIdAndType($id,$type);
    }

}

?>