<?php
/**
 * Created by PhpStorm.
 * User: 张鑫
 * Date: 2019/3/20
 * Time: 10:50
 */
class Table_service_problem extends Table {


    private static  $pre = "problem_";

    static protected function struct($data){
        $r = array();
        $r['id']                    = $data['problem_id'];
        $r['keyword']               = $data['problem_keyword'];
        $r['content']                = $data['problem_content'];
        $r['type']        = $data['problem_type'];
        $r['url']                 = $data['problem_url'];
        $r['url_type']                 = $data['problem_url_type'];
        $r['category']           = $data['problem_category'];
        $r['code']                 = $data['problem_code'];
        $r['video_cover']               = $data['problem_video_cover'];
        $r['cover']               = $data['problem_cover'];
        $r['addtime']               = $data['problem_addtime'];
        $r['lastupdatetime']                = $data['problem_lastupdatetime'];
        $r['operator']                = $data['problem_operator'];
        $r['status']                = $data['problem_status'];
        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "keyword"=>"string",
            "content"=>"string",
            "type"=>"number",
            "url"=>"string",
            "url_type"=>"number",
            "category"=>"number",
            "code"=>"string",
            "video_cover"=>"string",
            "cover"=>"string",
            "addtime"=>"number",
            "lastupdatetime"=>"number",
            "status"=>"number",
            "operator"=>"number"

        );

        return isset($typeAttr[$attr])?$typeAttr[$attr]:$typeAttr;
    }


    /**
     * @param $attrs
     * @return mixed
     * zx
     * 添加方法
     */

    static public function add($attrs){

        global $mypdo;

        $params = array();
        foreach ($attrs as $key=>$value)
        {
            $type = self::getTypeByAttr($key);
            $params[self::$pre.$key] =  array($type,$value);

        }

        $r = $mypdo->sqlinsert($mypdo->prefix.'service_problem', $params);
        return $r;
    }


    /**
     * @param $id
     * @param $attrs
     * @return mixed
     *
     */
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
            "problem_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'service_problem', $params, $where);
        return $r;
    }

    /**
     * @param $id
     * @return array|mixed
     *
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."service_problem where problem_id = $id limit 1";

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
     * @param $params
     * @return array
     *
     */
    static public function getList($params){
        global $mypdo;
        $sql = "select * from ".$mypdo->prefix."service_problem ";
        $where=" where problem_status = 1 ";

        $sql.=$where;
        if(!empty($params["category"]))
        {
            $sql .= " and problem_category ='".$params["category"]."'";
        }

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

    /**
     * @param $id
     * @return mixed
     *
     */

    static public function dels($id){

        global $mypdo;


        $params = array(
            "problem_status" => array('number', -1)
        );

        $where = array(
            "problem_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'service_problem', $params, $where);
        return $r;
    }

    /**
     * @param $params
     * @return null
     */
    static public function getCount($params){
        global $mypdo;
        $sql = "select count(1) as act from ".$mypdo->prefix."service_problem ";
        $where=" where problem_status =1 ";



        $sql.=$where;
        $rs = $mypdo->sqlQuery($sql);
        if($rs){
            return $rs[0][0];
        }else{
            return null;
        }
    }

    /**
     * @param $id
     * @return array|mixed
     */
    static public function getBeginInfo(){
        global $mypdo;


        $sql = "select * from ".$mypdo->prefix."service_problem where problem_category = 1 and problem_status =1 limit 1";


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
     *
     */
    static public function getInfoByCode($code){
        global $mypdo;


        $sql = "select * from ".$mypdo->prefix."service_problem where problem_code = '".$code."' and problem_status =1 limit 1";

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
     * @return array|mixed
     */
    static public function getEndInfo(){
        global $mypdo;


        $sql = "select * from ".$mypdo->prefix."service_problem where problem_category = 2 and problem_status =1 limit 1";


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
     *
     */
    static public function getInfoByKeyword($keyword){
        global $mypdo;


        $sql = "select * from ".$mypdo->prefix."service_problem where problem_keyword = '".$keyword."' and problem_status =1 limit 1";

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


}