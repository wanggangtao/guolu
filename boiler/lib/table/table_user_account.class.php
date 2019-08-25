<?php


class Table_user_account extends Table {


    private static  $pre = "account_";

    static protected function struct($data){
    $r = array();
    $r['id']                    = $data['account_id'];
    $r['name']               = $data['account_name'];
    $r['phone']                = $data['account_phone'];
    $r['openid']        = $data['account_openid'];
    $r['province_id']                 = $data['account_province_id'];
    $r['city_id']           = $data['account_city_id'];
    $r['area_id']                 = $data['account_area_id'];
    $r['community_id']               = $data['account_community_id'];
    $r['detail_addres']               = $data['account_detail_addres'];
    $r['status']                = $data['account_status'];
    $r['addtime']                 = $data['account_addtime'];
    $r['deltime']               = $data['account_deltime'];
    $r['contact_address']               = $data['account_contact_address'];
    $r['product_code']                = $data['account_product_code'];
    $r['nickname']                = $data['account_nickname'];

    return $r;
}

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "name"=>"string",
            "phone"=>"string",
            "openid"=>"string",
            "province_id"=>"number",
            "city_id"=>"number",
            "area_id"=>"number",
            "community_id"=>"number",
            "detail_addres"=>"string",
            "status"=>"number",
            "addtime"=>"number",
            "deltime"=>"number",
            "contact_address"=>"string",
            "nickname"=>"string",
            "product_code"=>"string"
        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }



    static public function add($attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }

        $r = $mypdo->sqlinsert($mypdo->prefix.'user_account', $params);
        return $r;
    }


    static public function update($id,$attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }
        //where条件
        $where = array(
            "account_id" => array('number', $id)
        );
//        print_r($where);
//        exit();
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'user_account', $params, $where);
        return $r;
    }

    /**
     * Table_burner_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $burnerId  燃烧器ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."user_account where account_id = $id limit 1";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return $r;
        }
    }
    static public function getInfoByPhone($phone){
        global $mypdo;

        $phone = $mypdo->sql_check_input(array('number', $phone));

        $sql = "select * from ".$mypdo->prefix."user_account where account_phone = $phone limit 1";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return $r;
        }
    }


    static public function getList($params){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."user_account ";
        $where=" where account_status =1 ";

        if(isset($params["name"])){
            $name = $mypdo->sql_check_input(array('string', "%".$params["name"]."%"));
            $where .= " and account_name like $name";
        }

        if(isset($params["phone"])){
            $phone = $mypdo->sql_check_input(array('string', "%".$params["phone"]."%"));
            $where .= " and account_phone like $phone";
        }

        if(isset($params["contact_address"])){
            $address = $mypdo->sql_check_input(array('string', "%".$params["contact_address"]."%"));
            $where .= " and account_contact_address like $address";
        }

        if(isset($params["product_code"])){
            $address = $mypdo->sql_check_input(array('string', "%".$params["product_code"]."%"));
            $where .= " and account_product_code like $address";
        }
        if(isset($params["nickname"])){
            $address = $mypdo->sql_check_input(array('string', "%".$params["nickname"]."%"));
            $where .= " and account_nickname like $address";
        }

        $sql.=$where;
        $sql.=" order by account_id desc";
        $limit = "";
        if(!empty(isset($params["page"]))){
            $start = ($params["page"] - 1)*$params["pageSize"];
            $limit = " limit {$start},{$params["pageSize"]}";
        }

        $sql .= $limit;

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }

    }


    static public function dels($id){

        global $mypdo;


        $params = array(
            "account_status" => array('number', -1)
        );

        $where = array(
            "account_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'user_account', $params, $where);
        return $r;
    }

    static public function getCount($params){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."user_account ";
        $where=" where account_status =1 ";


        if(isset($params["name"])){
            $name = $mypdo->sql_check_input(array('string', "%".$params["name"]."%"));
            $where .= " and account_name like $name";
        }

        if(isset($params["phone"])){
            $phone = $mypdo->sql_check_input(array('string', "%".$params["phone"]."%"));
            $where .= " and account_phone like $phone";
        }

        if(isset($params["contact_address"])){
            $address = $mypdo->sql_check_input(array('string', "%".$params["contact_address"]."%"));
            $where .= " and account_contact_address like $address";
        }

        if(isset($params["product_code"])){
            $address = $mypdo->sql_check_input(array('string', "%".$params["product_code"]."%"));
            $where .= " and account_product_code like $address";
        }
        if(isset($params["nickname"])){
            $address = $mypdo->sql_check_input(array('string', "%".$params["nickname"]."%"));
            $where .= " and account_nickname like $address";
        }

        $sql.=$where;
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0][0];
        }else{
            return null;
        }
    }


    /**
     * Table_burner_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $burnerId  燃烧器ID
     *
     * @return
     */
    static public function getInfoByBarCode($barCode){
        global $mypdo;

        $barCode = $mypdo->sql_check_input(array('string', $barCode));

        $sql = "select * from ".$mypdo->prefix."user_account where account_product_code = $barCode and account_status = 1 limit 1";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return $r;
        }
    }
    /**
     * Table_burner_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $burnerId  燃烧器ID
     *
     * @return
     */

    /**
     * Table_burner_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $burnerId  燃烧器ID
     *
     * @return
     */
    static public function getInfoByOpenid($openId){
        global $mypdo;

        $openId = $mypdo->sql_check_input(array('string', $openId));

        $sql = "select * from ".$mypdo->prefix."user_account where account_openid = $openId and account_status = 1 limit 1";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return $r;
        }

    }


    static public function login_out($openId){

        global $mypdo;

        $openId = $mypdo->sql_check_input(array('string', $openId));


        $sql = "UPDATE boiler_user_account SET account_openid = '' WHERE account_openid = {$openId}";
//echo $sql;
        $num = $mypdo->pdo->exec($sql);

        return $num;
    }

    /**
     * @param $openId
     * @return array|mixed
     */
    static public function getAllByOpenid($openId){
        global $mypdo;

        $openId = $mypdo->sql_check_input(array('string', $openId));

//        $sql = "select * from ".$mypdo->prefix."user_account as us , ".$mypdo->prefix."product_info as pinfo ".
//
//        " where us.account_product_code =pinfo.info_code  and us.account_openid = $openId and us.account_status = 1 limit 1";\\

        $sql = "select * from ".$mypdo->prefix."user_account as us left join  ".$mypdo->prefix."product_info as pinfo ".
            "on us.account_product_code=pinfo.info_code where us.account_openid = $openId and us.account_status = 1   limit 1";
//

        $rs = $mypdo->sqlQuery($sql);

        if($rs){
            return $rs[0];
        }else{
            return null;
        }

    }

    static public function updataBarCode($code){

        global $mypdo;

        $code = $mypdo->sql_check_input(array('string', $code));


        $sql = "UPDATE boiler_user_account SET account_product_code = '',account_province_id =0,account_city_id =0, account_area_id = 0 , account_community_id =0,
                account_detail_addres = '',account_contact_address =''
                WHERE account_product_code = {$code}";

        $num = $mypdo->pdo->exec($sql);

        return $num;
    }
    static public function getAvailabelCode($code ){

        global $mypdo;
        $sql = "SELECT account_product_code FROM boiler_user_account LEFT JOIN boiler_repair_order on order_bar_code = account_product_code where account_status = 1 and 
                  (order_status = 3 or order_status is null) ";
        if(!empty($code)){
            $sql .=   " and  account_product_code = " . $code;
        }
        $sql .=   " ORDER BY account_product_code ASC";

        $rs = $mypdo->sqlQuery($sql);

        if($rs){
            return $rs;
        }else{
            return null;
        }
    }
    static public function getIdByLikeName($name){
        global $mypdo;

//        $name = $mypdo->sql_check_input(array('string', $name));

        $sql = "select account_id from ".$mypdo->prefix."user_account where account_name   LIKE '%".$name."%' ";

        $rs = $mypdo->sqlQuery($sql);

        return $rs;

    }


    /**
     * Table_burner_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $burnerId  燃烧器ID
     *
     * @return
     */
    static public function getUserIdByCommunity($community_id){
        global $mypdo;


        $sql = "select * from ".$mypdo->prefix."user_account where 1 = 1 and account_status = 1 ";

        if(!empty($community_id)){
            $sql .=" and account_community_id in ({$community_id})";
        }else{
            $sql .=" and account_community_id != -1";
        }


        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r;
        }else{
            return $r;
        }
    }


}
?>