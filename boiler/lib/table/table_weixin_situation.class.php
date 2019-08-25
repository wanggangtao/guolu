<?php
/**
 * Created by PhpStorm.
 * User: hhx
 * Date: 2019/3/19
 * Time: 15:38
 */
class Table_weixin_situation extends Table
{
    static private $pre = "industry_";

    static protected function struct($data)
    {
        $r = array();
        $r['id'] = $data['industry_id'];
        $r['title'] = $data['industry_title'];
        $r['status'] = $data['industry_status'];
        $r['picurl'] = $data['industry_picurl'];
        $r['content'] = $data['industry_content'];
        //$r['http']       = $data['company_situation_http'];
        $r['if_top'] = $data['industry_if_top'];
        //$r['count']      = $data['company_situation_count'];
        $r['addtime'] = $data['industry_addtime'];

        return $r;
    }

    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id" => "number",
            "title" => "string",
            "content" => "string",
            "picurl" => "string",
            "if_top" => "number",
            "addtime" => "number"
        );

        return isset($typeAttr[$attr]) ? $typeAttr[$attr] : $typeAttr;
    }

    static public function getPageListWeiXin($page, $pageSize, $count)
    {
        global $mypdo;

        //$where = " where ".$status." = 1 ";

        /*if(!empty($type)){
            $type = $mypdo->sql_check_input(array('number', $type));
            $where .= " and industry_status = $type ";
        }*/
        if ($count == 0) {
            $sql = "select count(1) as ct from " . $mypdo->prefix . "weixin_company_situation where industry_status = 1";
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        } else {
            $sql = "select * from " . $mypdo->prefix . "weixin_company_situation where industry_status = 1";
            $sql .= " order by industry_if_top desc, industry_addtime desc";

            $limit = "";
            if (!empty($page)) {
                $start = ($page - 1) * $pageSize;
                $limit = " limit {$start},{$pageSize}";
            }

            $sql .= $limit;
            $rs = $mypdo->sqlQuery($sql);
            $r = array();
            if ($rs) {
                foreach ($rs as $key => $val) {
                    $r[$key] = self::struct($val);
                }
            }

            return $r;
        }
    }

    static public function getListWeiXin()
    {
        global $mypdo;

        //$where = " where ".$status." = 1 ";

        /*if(!empty($type)){
            $type = $mypdo->sql_check_input(array('number', $type));
            $where .= " and industry_status = $type ";
        }
        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."weixin_company_situation".$where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{*/
        $sql = "select * from " . $mypdo->prefix . "weixin_company_situation where industry_status = 1";
        $sql .= " order by industry_if_top desc, industry_addtime desc";

        /*$limit = "";
        if(!empty($page)){
            $start = ($page - 1)*$pageSize;
            $limit = " limit {$start},{$pageSize}";
        }

        $sql .= $limit;*/
        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if ($rs) {
            foreach ($rs as $key => $val) {
                $r[$key] = self::struct($val);
            }
        }

        return $r;
    }

    static public function getInfoById($sid)
    {
        global $mypdo;

        $sid = $mypdo->sql_check_input(array('number', $sid));
        $where = " where industry_id = " . $sid;
        $sql = "select * from " . $mypdo->prefix . "weixin_company_situation " . $where . " limit 1";
        $rs = $mypdo->sqlQuery($sql);
        $r = array();
        if ($rs) {
            foreach ($rs as $key => $val) {
                $r[$key] = self::struct($val);
            }
            return $r[0];
        } else {
            return $r;
        }
    }

    static public function add($params)
    {
        global $mypdo;

        $param = array();
        foreach ($params as $key => $value) {
            $type = self::getTypeByAttr($key);
            $param[self::$pre . $key] = array($type, $value);
        }
        $r = $mypdo->sqlinsert($mypdo->prefix . 'weixin_company_situation ', $param);
        return $r;
    }
    static public function edit($params,$id){
        global $mypdo;

        $param = array();
        $where[self::$pre.'id'] = array('number',$id);
        foreach($params as $key=>$val){
            $type = self::getTypeByAttr($key);
            $param[self::$pre.$key] = array($type,$val);
        }
        $r = $mypdo->sqlupdate($mypdo->prefix.'weixin_company_situation',$param,$where);
        return $r;
    }
    static public function del($id){
        global $mypdo;

        $param = array();
        $where[self::$pre .'id'] =array('number',$id);
        $param[self::$pre .'status'] =array('number',-1);

        $rs = $mypdo->sqlupdate($mypdo->prefix.'weixin_company_situation ', $param, $where);
        return $rs;
    }
}
?>