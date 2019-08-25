<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/5/25
 * Time: 11:13
 */

class Weixin_dx_community_item{

    static public function add($attrs){
        return Table_weixin_dx_community_item::add($attrs);
    }
    static public function getCommunityByRuleId($rule_id){
        return Table_weixin_dx_community_item::getCommunityByRuleId($rule_id);
    }

}