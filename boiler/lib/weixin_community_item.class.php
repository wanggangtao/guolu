<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 2019/5/24
 * Time: 19:26
 */

class Weixin_community_item{

    static public function add($attrs){
        return Table_weixin_community_item::add($attrs);
    }
    static public function getCommunityByRuleId($rule_id){
        return Table_weixin_community_item::getCommunityByRuleId($rule_id);
    }

    }