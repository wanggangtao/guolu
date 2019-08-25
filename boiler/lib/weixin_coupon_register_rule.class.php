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

class Weixin_coupon_register_rule {


    public function __construct()
    {

    }

    static public function add($attrs)
    {
        if (empty($attrs)) {
            throw new Exception('参数不能为空', 102);
        }
        $cname = Table_weixin_coupon_register_rule::getInfoByName($attrs['name']);
        if($cname) throw new MyException('规则名已存在', 102);
        $id = Table_weixin_coupon_register_rule::add($attrs);

        return $id;
    }

    static public function getInfoById($id)
    {
        return Table_weixin_coupon_register_rule::getInfoById($id);
    }

    static public function update($id, $attrs)
    {
        return Table_weixin_coupon_register_rule::update($id, $attrs);
    }

    static public function dels($id)
    {
        return Table_weixin_coupon_register_rule::dels($id);
    }

    static public function termination($id)
    {
        return Table_weixin_coupon_register_rule::termination($id);
    }

    static public function getList($params = array())
    {
        return Table_weixin_coupon_register_rule::getList($params);
    }

    static public function getNormalList($params = array())
    {
        return Table_weixin_coupon_register_rule::getNormalList($params);
    }
    static public function getCount($params = array())
    {
        return Table_weixin_coupon_register_rule::getCount($params);
    }

    static public function isRuleExist($start,$end)
    {
        return Table_weixin_coupon_register_rule::isRuleExist($start,$end);
    }

    static public function getNameById($id)
    {
        $row = self::getInfoById($id);

        return isset($row['name']) ? $row['name'] : "";
    }

    static public function getInfoByName($name)
    {
        return Table_weixin_coupon_register_rule::getInfoByName($name);
    }

    static public function getInfoByTime($nowTime){
        return Table_weixin_coupon_register_rule::getInfoByTime($nowTime);
    }
}

?>