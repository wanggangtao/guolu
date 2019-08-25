<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/5/25
 * Time: 11:20
 */

class Weixin_dx_coupon_item{

    static public function add($attrs){
        return Table_weixin_dx_coupon_item::add($attrs);
    }
    static public function getCouponByRuleId($rule_id){
        return Table_weixin_dx_coupon_item::getCouponByRuleId($rule_id);
    }
    static public function getInfoByTime($id){
        return Table_weixin_dx_coupon_item::getInfoByTime($id);
    }

    }