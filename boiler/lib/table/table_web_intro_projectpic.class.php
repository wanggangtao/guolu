<?php
/**
 * Table_web_intro_advantage.class.php 数据库表:公司介绍----优势
 * User: Lin
 * Date: 2018/12/27 0027
 * Time: 下午 2:11
 */

class Table_web_intro_projectpic
{
    private static  $pre = "picture_";

    static protected function struct($data){
        $r = array();
        $r['id']         = $data['picture_id'];
        $r['weight']      = $data['picture_weight'];
        $r['picurl']       = $data['picture_picurl'];
        $r['http']       = $data['picture_http'];
        $r['display']    =$data['picture_display'];
        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "weight"=>"number",
            "picurl"=>"string",
            "http"=>"string",
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

        $r = $mypdo->sqlinsert($mypdo->prefix.'web_company_introduction_project_picture', $params);
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
            "picture_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'web_company_introduction_project_picture', $params, $where);
        return $r;
    }

    /**
     * Table_web_introduction::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."web_company_introduction_project_picture where picture_id = $id limit 1";

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
     * Table_web_introduction::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @return
     */
    static public function getPageList($page, $pageSize, $count){
        global $mypdo;

        $where = " where 1 = 1 ";
        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."web_company_introduction_project_picture".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }
        else{
            $sql = "select * from ".$mypdo->prefix."web_company_introduction_project_picture".$where;
            $sql .=" order by picture_weight desc";

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
//        if(!empty($params['display'])){
//            $where .=" and picture_display=1";
//        };

    }



    /**
     * Table_web_introduction::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "picture_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'web_company_introduction_project_picture', $where);
    }

    static public function getListQinDuan($params){
        global $mypdo;

        $where = " where 1 = 1 ";
        if(!empty($params['display'])){
           $where .=" and picture_display=1";
        };
            $sql = "select * from ".$mypdo->prefix."web_company_introduction_project_picture".$where;
            $sql .=" order by picture_weight desc";



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