<?php

/**
 * weixin_user_coupon.class.php  优惠券实体类
 *
 * @version       v0.03
 * @create time   2014/09/04
 * @update time   2016/02/18 2016/3/25
 * @author        dxl wzp jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Weixin_user_coupon{

    const NO_USE_QOUPON_NAME = "不使用优惠券";


    public function __construct()
    {

    }

    static public function add($attrs)
    {
        if (empty($attrs)) {
            throw new Exception('参数不能为空', 102);
        }
        $id = Table_weixin_user_coupon::add($attrs);

        return $id;
    }

    static public function getInfoById($id ,$type = 0 )
    {
        return Table_weixin_user_coupon::getInfoById($id,$type);
    }

    static public function getInfoByUid($uid)
    {
        return Table_weixin_user_coupon::getInfoByUid($uid);
    }

    static public function getInfoByCid($cid)
    {
        return Table_weixin_user_coupon::getInfoByCid($cid);
    }

    static public function update($id, $attrs)
    {
        return Table_weixin_user_coupon::update($id, $attrs);
    }

    static public function dels($id)
    {
        return Table_weixin_user_coupon::dels($id);
    }

    static public function used($id)
    {
        return Table_weixin_user_coupon::used($id);
    }

    static public function forbidden($id)
    {
        return Table_weixin_user_coupon::forbidden($id);
    }

    static public function getList($params = array())
    {
        if(!empty($params['value'])){
            $person_info =  User_account::getIdByLikeName($params['value']);
            $person_array = array_column($person_info,'account_id');
            if(!empty($person_array)){
                $person_str = implode(",", $person_array);
                $params['accountName'] = $person_str;
            }

        }
        return Table_weixin_user_coupon::getList($params);
    }

    static public function getListALLInfo($id,$status = 1,$params =array())
    {
        return Table_weixin_user_coupon::getListALLInfo($id,$status,$params);
    }

    static public function getCount($params = array())
    {
        if(!empty($params['value'])){
            $person_info =  User_account::getIdByLikeName($params['value']);
            $person_array = array_column($person_info,'account_id');
            if(!empty($person_array)){
                $person_str = implode(",", $person_array);
                $params['accountName'] = $person_str;
            }

        }
        return Table_weixin_user_coupon::getCount($params);
    }

//    static public function getNameById($id)
//    {
//        $row = self::getInfoById($id);
//
//        return isset($row['name']) ? $row['name'] : "";
//    }

//    static public function getInfoByName($name)
//    {
//        return Table_weixin_coupon_register_rule::getInfoByName($name);
//    }

    static public function addUserCoupon($userid , $community){



        $nowTime = time();
        //注册发送优惠券
        $nowRule = Weixin_coupon_register_rule::getInfoByTime($nowTime);

        $register_flag  = 2;
        if(!empty($nowRule)){

            $community_list = Weixin_community_item::getCommunityByRuleId($nowRule[0]['rule_id']);
            $community_str = implode(",",$community_list);

            if (!in_array($community,$community_list) && $community_str != -1){
                $register_flag  = 2;
            }else{
                foreach ($nowRule as $item){

                    $coupon_info  = Weixin_coupon::getInfoById($item['item_coupon_id']);

                    if($coupon_info['total'] - $coupon_info['received'] <= 0 && $coupon_info['total'] != -1 ){
                        continue;
                    }

                    if($coupon_info['validity_period'] != 0 &&  ( time() - $coupon_info['endtime']) <0){
                        continue;
                    }


                    if($coupon_info['validity_period'] != 0){

                        $day = $coupon_info['validity_period'];
                        $starttime =time();
                        $endtime   = strtotime(date("Y-m-d",strtotime("+$day day"))) + 86399;

                    }else{
                        $starttime =$coupon_info['starttime'];
                        $endtime   = $coupon_info['endtime'];
                    }

                    $attrs =array(
                        "uid" => $userid,
                        "cid" => $item['item_coupon_id'],
                        "addtime" => time(),
                        "starttime"=>$starttime ,
                        "endtime"=>$endtime ,
                    );

                    self::add($attrs);

                    $newReceived = $coupon_info['received']+ 1;


                    Weixin_coupon::update($item['item_coupon_id'],array("received"=>$newReceived));
                    $register_flag = 1;
                }
            }


        }
        //定向发送优惠券
        $dx_flag = 2;
        $dx_rule = Weixin_coupon_dx_rule::getRuleByAttrs(array('now_time' => $nowTime));

        if(!empty($dx_rule)){
            $coupon_list = Weixin_dx_coupon_item::getCouponByRuleId($dx_rule['id']);
            $community_array = Weixin_dx_community_item::getCommunityByRuleId($dx_rule['id']);
            $community_str = implode(",",$community_array);

            if (!in_array($community,$community_array)  && $community_str != -1){
                $dx_flag  = 2;
            }else{
                foreach ($coupon_list as $coupon_item){

                    $coupon_info  = Weixin_coupon::getInfoById($coupon_item);


                    if($coupon_info['total'] - $coupon_info['received'] <= 0 && $coupon_info['total'] != -1 ){
                        continue;
                    }
                    if($coupon_info['validity_period'] != 0 &&  ( time() - $coupon_info['endtime']) <0){
                        continue;
                    }


                    if($coupon_info['validity_period'] != 0){

                        $day = $coupon_info['validity_period'];
                        $starttime =time();
                        $endtime   = strtotime(date("Y-m-d",strtotime("+$day day"))) + 86399;

                    }else{
                        $starttime =$coupon_info['starttime'];
                        $endtime   = $coupon_info['endtime'];
                    }

                    $attrs =array(
                        "uid" => $userid,
                        "cid" => $coupon_item,
                        "addtime" => time(),
                        "starttime"=>$starttime ,
                        "endtime"=>$endtime ,
                    );

                    self::add($attrs);

                    $newReceived = $coupon_info['received']+ 1;

                    Weixin_coupon::update($coupon_item,array("received"=>$newReceived));

                    $dx_flag = 1;
                }
            }

        }

        if($dx_flag == 1 || $register_flag == 1){
            return 1;
        }else {
            return 2;
        }



    }

    static public function getInfoByIdAndType($id,$type){
        return Table_weixin_user_coupon::getInfoByIdAndType($id,$type);
    }
    static public function getInfoByCidAndUid($uid, $cid)
    {
        return Table_weixin_user_coupon::getInfoByCidAndUid($uid, $cid);
    }


    /***
     * @param int $type
     *
     * $params['uid'] 查找当前用户所有优惠券
     * 其他 查找当前时间用户可使用优惠券
     *
     */
    static public function getMyCouponInfo($params)
    {
        return Table_weixin_user_coupon::getMyCouponInfo($params);
    }

    }

?>