<?php
/**
 * table_afterservice.class.php 数据库表:售后服务
 * User: lp
 * Date: 2019/08/11
 * Time: 15:57
 */

class table_afterservice
{
    private static  $pre = "aftersales_";

    static protected function struct($data){
        $r = array();
        $r['id']             = $data['aftersales_id'];
        $r['weight']        = $data['aftersales_weight'];
        $r['title']       = $data['aftersales_title'];
        $r['content']       = $data['aftersales_content'];
        $r['picture']       = $data['aftersales_picture'];
        $r['state']       = $data['aftersales_state'];
        $r['sign']        = $data['aftersales_sign'];
        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"expression",
            "weight"=>"number",
            "title"=>"string",
            "content"=>"string",
            "picture"=>"string",
            "state"=>"number",
             "sign"=>"number"
        );
//        echo 111;
////        print_r($typeAttr);
//        echo 000;
////        print_r($typeAttr[$attr]);
//        print_r($attr);
//        echo $typeAttr[$attr];
//        die();
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
        $r = $mypdo->sqlinsert($mypdo->prefix.'aftersales_service', $params);
        return $r;
    }

/*    static public function add($attrs){

        global $mypdo;

        //写入数据库
        $param = array (
            'aftersales_id'   => array('expression',"null" ),
            'aftersales_weight'  => array('string', ""),
            'aftersales_title'      => array('string', $attrs['title']),
            'aftersales_content'     => array('string', $attrs['content']),
            'aftersales_picture'   => array('string', $attrs["picture"]),
            'aftersales_state'    =>  array('expression',"1"),
            'aftersales_sign'    =>  array('number',"sign")

        );
        return $mypdo->sqlinsert($mypdo->prefix.'aftersales_service', $param);
    }*/


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
            "aftersales_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'aftersales_service', $params, $where);
        return $r;
    }

    /**
     * table_afterservice::getInfoById() 根据ID获取详情
     * @param Integer $id  项目ID
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."aftersales_service where aftersales_id = $id limit 1";

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
     * table_afterservice::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $sign){
        global $mypdo;

//        $where = " where 1 = 1 ";
        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."aftersales_service where aftersales_sign = $sign and aftersales_state = 1";

            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."aftersales_service where aftersales_sign = $sign and aftersales_state = 1";
            $sql .=" order by aftersales_weight desc";

            $limit = "";
            if(!empty($page)){
                $start = ($page - 1)*$pageSize;
                $limit = " limit {$start},{$pageSize}";
            }

            $sql .= $limit;

            $rs = $mypdo->sqlQuery($sql);
            $r = array();
            if($rs){
                foreach($rs as $key => $val){
                    $r[$key] = self::struct($val);
                }
            }

            return $r;
        }
    }

    /**
     * table_afterservice::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "aftersales_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'aftersales_service', $where);
    }

    static public function getList(){
        global $mypdo;

        $sql = "select * from ".$mypdo->prefix."aftersales_service";
        $sql .=" order by aftersales_weight desc";

        //  echo $sql;

        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }

        return $r;
    }

    static public function getListByType($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."aftersales_service where aftersales_id = $id";
        $sql .=" order by aftersales_weight desc";
        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }

        return $r;
    }


}
