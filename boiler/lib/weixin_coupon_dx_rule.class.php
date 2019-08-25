<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/5/25
 * Time: 11:24
 */

class Weixin_coupon_dx_rule {


    public function __construct()
    {

    }

    static public function add($attrs)
    {
        if (empty($attrs)) {
            throw new Exception('参数不能为空', 102);
        }
        $cname = Table_weixin_coupon_dx_rule::getInfoByName($attrs['name']);
        if($cname) throw new MyException('规则名已存在', 102);
        $id = Table_weixin_coupon_dx_rule::add($attrs);

        return $id;
    }

    static public function getInfoById($id)
    {
        return Table_weixin_coupon_dx_rule::getInfoById($id);
    }

    static public function update($id, $attrs)
    {
        return Table_weixin_coupon_dx_rule::update($id, $attrs);
    }

    static public function dels($id)
    {
        return Table_weixin_coupon_dx_rule::dels($id);
    }
    static public function termination($id)
    {
        return Table_weixin_coupon_dx_rule::termination($id);
    }

    static public function getList($params = array())
    {
        return Table_weixin_coupon_dx_rule::getList($params);
    }

    static public function getCount($params = array())
    {
        return Table_weixin_coupon_dx_rule::getCount($params);
    }

    static public function getNameById($id)
    {
        $row = self::getInfoById($id);

        return isset($row['name']) ? $row['name'] : "";
    }

    static public function getInfoByName($name)
    {
        return Table_weixin_coupon_dx_rule::getInfoByName($name);
    }

    static public function getRuleByAttrs($params = array())
    {
        return Table_weixin_coupon_dx_rule::getRuleByAttrs($params);
    }

}