<?php

/**
 * Weixin_register_rule_item.class.php  优惠券注册发放数量类
 *
 * @version       v0.03
 * @create time   2014/09/04
 * @update time   2016/02/18 2016/3/25
 * @author        dxl wzp jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Weixin_register_rule_item {


    public function __construct()
    {

    }

    static public function add($attrs)
    {
        if (empty($attrs)) {
            throw new Exception('参数不能为空', 102);
        }
        $id = Table_weixin_register_rule_item::add($attrs);

        return $id;
    }

    static public function getInfoById($id)
    {
        return Table_weixin_register_rule_item::getInfoById($id);
    }

    static public function getInfoByRuleId($ruleid)
    {
        return Table_weixin_register_rule_item::getInfoByRuleId($ruleid);
    }

    static public function getInfoByCouponId($couponid)
    {
        return Table_weixin_register_rule_item::getInfoByCouponId($couponid);
    }

    static public function update($id, $attrs)
    {
        return Table_weixin_register_rule_item::update($id, $attrs);
    }

//    static public function dels($id)
//    {
//        return Table_weixin_register_rule_item::dels($id);
//    }

    static public function getList($params = array())
    {
        return Table_weixin_register_rule_item::getList($params);
    }

    static public function getCount($params = array())
    {
        return Table_weixin_register_rule_item::getCount($params);
    }

    static public function getNameById($id)
    {
        $row = self::getInfoById($id);

        return isset($row['name']) ? $row['name'] : "";
    }

//    static public function getInfoByName($name)
//    {
//        return Table_weixin_register_rule_item::getInfoByName($name);
//    }
}

?>