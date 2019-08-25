<?php
/**
 * table_web_situation:公司动态table类
 * Created by kjb.
 * Date: 2018/12/5
 * Time: 10:11
 */

class Table_web_situation
{

    private static  $pre = "company_situation_";

    static protected function struct($data){
        $r = array();
        $r['id']         = $data['company_situation_id'];
        $r['title']      = $data['company_situation_title'];
        $r['type']       = $data['company_situation_type'];
        $r['picurl']     = $data['company_situation_picurl'];
        $r['content']    = $data['company_situation_content'];
        $r['http']       = $data['company_situation_http'];
        $r['if_top']      = $data['company_situation_if_top'];
        $r['count']      = $data['company_situation_count'];
        $r['addtime']    = $data['company_situation_addtime'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "title"=>"string",
            "type"=>"number",
            "picurl"=>"string",
            "http"=>"string",
            "content"=>"string",
            "count"=>"number",
            "if_top"=>"number",
            "addtime"=>"number"

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

        $r = $mypdo->sqlinsert($mypdo->prefix.'web_company_situation', $params);
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
            "company_situation_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'web_company_situation', $params, $where);
        return $r;
    }

    /**
     * Table_web_situation::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."web_company_situation where company_situation_id = $id limit 1";

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
     * Table_web_situation::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @return
     */
    static public function getPageList($page, $pageSize, $count, $type){
        global $mypdo;

        $where = " where 1 = 1 ";

        if(!empty($type)){
            $type = $mypdo->sql_check_input(array('number', $type));
            $where .= " and company_situation_type = $type ";
        }
        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."web_company_situation".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."web_company_situation".$where;
            $sql .=" order by company_situation_addtime desc";

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
     * Table_web_situation::getPageListWeb() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param number $type             类型
     * @return
     */
    static public function getPageListWeb($page, $pageSize, $count, $type){
        global $mypdo;

        $where = " where 1 = 1 ";

        if(!empty($type)){
            $type = $mypdo->sql_check_input(array('number', $type));
            $where .= " and company_situation_type = $type ";
        }
        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."web_company_situation".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."web_company_situation".$where;
            $sql .=" order by company_situation_if_top desc, company_situation_addtime desc";

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
     * Table_web_situation::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "company_situation_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'web_company_situation', $where);
    }

    static public function getListQianDuan($params){
        global $mypdo;

        $sql="select * from ".$mypdo->prefix."web_company_situation";

        $where=" where company_situation_is_good=1";
        if(!empty($params['id'])){
            $where.=" and company_situation_order={$params['id']}";
        }

        $sql .= $where;

        $sql .=" order by company_situation_if_top desc, company_situation_addtime desc";


        $rs=$mypdo->sqlQuery($sql);
        $r=array();
        if($rs){
            foreach ($rs as $key => $val){
                $r[$key]=self::struct($val);
            }

            return $r;
        }else{
            return $r;
        }

    }

    static public function getOtherList(){
        global $mypdo;

        $sql="select * from ".$mypdo->prefix."web_company_situation";

        $where=" where company_situation_is_good = -1";

        $sql .= $where;

        $sql .=" order by company_situation_if_top desc, company_situation_addtime desc";


        $rs=$mypdo->sqlQuery($sql);
        $r=array();
        if($rs){
            foreach ($rs as $key => $val){
                $r[$key]=self::struct($val);
            }

            return $r;
        }else{
            return $r;
        }

    }
}