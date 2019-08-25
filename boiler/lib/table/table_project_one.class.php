<?php

/**
 * table_project_one.class.php 数据库表:项目第一阶段表
 *
 * @version       v0.01
 * @createtime    2018/6/25
 * @updatetime    2018/6/25
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Table_project_one extends Table {


    private static  $pre = "po_";

    static protected function struct($data){
        $r = array();
        $r['id']                           = $data['po_id'];
        $r['project_id']                   = $data['po_project_id'];
        $r['project_name']                 = $data['po_project_name'];
        $r['project_detail']               = $data['po_project_detail'];
        $r['project_lat']                  = $data['po_project_lat'];
        $r['project_long']                 = $data['po_project_long'];
        $r['project_type']                 = $data['po_project_type'];
        $r['project_partya']               = $data['po_project_partya'];
        $r['project_partya_address']       = $data['po_project_partya_address'];
        $r['project_partya_desc']          = $data['po_project_partya_desc'];
        $r['project_partya_pic']           = $data['po_project_partya_pic'];
        $r['project_linkman']              = $data['po_project_linkman'];
        $r['project_linktel']              = $data['po_project_linktel'];
        $r['project_history']              = $data['po_project_history'];
        $r['project_history_attr']         = $data['po_project_history_attr'];
        $r['project_linkposition']         = $data['po_project_linkposition'];
        $r['project_boiler_num']           = $data['po_project_boiler_num'];
        $r['project_boiler_tonnage']       = $data['po_project_boiler_tonnage'];
        $r['project_wallboiler_num']       = $data['po_project_wallboiler_num'];
        $r['project_brand']                = $data['po_project_brand'];
        $r['project_xinghao']              = $data['po_project_xinghao'];
        $r['project_build_type']           = $data['po_project_build_type'];
        $r['project_isnew']                = $data['po_project_isnew'];
        $r['project_pre_buildtime']        = $data['po_project_pre_buildtime'];
        $r['project_competitive_brand']    = $data['po_project_competitive_brand'];
        $r['project_competitive_desc']     = $data['po_project_competitive_desc'];
        $r['project_desc']                 = $data['po_project_desc'];
        $r['project_addtime']              = $data['po_project_addtime'];
        $r['project_lastupdate']           = $data['po_project_lastupdate'];

        return $r;
    }



    static public function getTypeByAttr($attr)
    {
        $typeAttr = array(
            "id"=>"number",
            "project_id"=>"number",
            "project_name"=>"string",
            "project_detail"=>"string",
            "project_lat"=>"string",
            "project_long"=>"string",
            "project_type"=>"number",
            "project_partya"=>"string",
            "project_partya_address"=>"string",
            "project_partya_desc"=>"string",
            "project_partya_pic"=>"string",
            "project_linkman"=>"string",
            "project_linktel"=>"string",
            "project_history"=>"string",
            "project_history_attr"=>"string",
            "project_linkposition"=>"string",
            "project_boiler_num"=>"number",
            "project_boiler_tonnage"=>"number",
            "project_wallboiler_num"=>"number",
            "project_brand"=>"string",
            "project_xinghao"=>"string",
            "project_build_type"=>"number",
            "project_isnew"=>"number",
            "project_pre_buildtime"=>"number",
            "project_competitive_brand"=>"string",
            "project_competitive_desc"=>"string",
            "project_desc"=>"string",
            "project_addtime"=>"number",
            "project_lastupdate"=>"number"
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
        $r = $mypdo->sqlinsert($mypdo->prefix.'project_one', $params);
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
            "po_id" => array('number', $id)
        );
        //返回结果
        $r = $mypdo->sqlupdate($mypdo->prefix.'project_one', $params, $where);
        return $r;
    }

    /**
     * Table_project_one::getInfoById() 根据ID获取详情
     *
     * @param Integer $id  ID
     *
     * @return
     */
    static public function getInfoById($id){
        global $mypdo;

        $id = $mypdo->sql_check_input(array('number', $id));

        $sql = "select * from ".$mypdo->prefix."project_one where po_id = $id limit 1";

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
     * Table_project_one::getInfoByProjectId() 根据项目ID获取详情
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function getInfoByProjectId($prid){
        global $mypdo;

        $prid = $mypdo->sql_check_input(array('number', $prid));

        $sql = "select * from ".$mypdo->prefix."project_one where po_project_id = $prid limit 1";

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
     * Table_project_one::getInfoByLinktel() 根据项目联系人电话获取详情
     *
     * @param string $tel  项目联系人电话
     *
     * @return
     */
    static public function getInfoByLinktel($tel){
        global $mypdo;

        $tel = $mypdo->sql_check_input(array('number', $tel));

        $sql = "select * from ".$mypdo->prefix."project_one where 	po_project_linktel = $tel ";

        $rs = $mypdo->sqlQuery($sql);
        $r  = array();
        if($rs){
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
        }
        return $r;
    }

    /**
     * Table_project_one::del() 根据ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function del($id){

        global $mypdo;

        $where = array(
            "po_id" => array('number', $id)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_one', $where);
    }

    /**
     * Table_project_one::delByProjectId() 根据项目ID删除数据
     *
     * @param Integer $id  项目ID
     *
     * @return
     */
    static public function delByProjectId($prid){

        global $mypdo;

        $where = array(
            "po_project_id" => array('number', $prid)
        );

        return $mypdo->sqldelete($mypdo->prefix.'project_one', $where);
    }


    /**
     * Table_project::getPageList() 根据条件列出所有信息
     * @param number $page             起始页，$page为0时取出全部
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $name             名称
     * @param string $detail           地址
     * @param string $partya           甲方单位
     * @param string $address          甲方地址
     * @param string $linkman          联系人
     * @param string $linktel          电话
     * @return
     */
    static public function getPageSameList($page, $pageSize, $count, $name, $detail, $partya, $address, $linkman, $linktel, $project_notsame_id){
        global $mypdo;

        $where = " where 1 <> 1 ";
        if($name){
            $nameo = $mypdo->sql_check_input(array('string', $name));
            $where .= " or (locate(po_project_name, $nameo) and po_project_name is not null and po_project_name <> '')";
            $namel = $mypdo->sql_check_input(array('string', '%'.$name.'%'));
            $where .= " or po_project_name like $namel ";
        }
        if($detail){
            $detailo = $mypdo->sql_check_input(array('string', $detail));
            $where .= " or (locate(po_project_detail, $detailo)  and po_project_detail is not null and po_project_detail <> '') ";
            $detaill = $mypdo->sql_check_input(array('string', '%'.$detail.'%'));
            $where .= " or po_project_detail like $detaill ";
        }
        if($partya){
            $partyao = $mypdo->sql_check_input(array('string', $partya));
            $where .= " or (locate(po_project_partya, $partyao)  and po_project_partya is not null and po_project_partya <> '')";
            $partyal = $mypdo->sql_check_input(array('string', '%'.$partya.'%'));
            $where .= " or po_project_partya like $partyal ";
        }
        if($address){
            $addresso = $mypdo->sql_check_input(array('string', $address));
            $where .= " or (locate(po_project_partya_address, $addresso) and po_project_partya_address is not null and po_project_partya_address <> '') ";
            $addressl = $mypdo->sql_check_input(array('string', '%'.$address.'%'));
            $where .= " or po_project_partya_address like $addressl ";
        }
        if($linkman){
            $linkman = $mypdo->sql_check_input(array('string', $linkman));
            $where .= " or po_project_linkman = $linkman ";
        }
        if($linktel){
            $linktel = $mypdo->sql_check_input(array('string', $linktel));
            $where .= " or po_project_linktel = $linktel ";
        }

        if($count == 0){
            $sql = "select count(1) as ct from ".$mypdo->prefix."project_one right join ".$mypdo->prefix."project on po_project_id = project_id and project_del_flag = 0 ";
            if($project_notsame_id){
                $sql .= " and po_project_id not in ({$project_notsame_id}) and project_level !=-1 ";
            }
            $sql .= $where;
            $rs = $mypdo->sqlQuery($sql);
            return $rs[0]['ct'];
        }else{
            $sql = "select * from ".$mypdo->prefix."project_one right join ".$mypdo->prefix."project on po_project_id = project_id and project_del_flag = 0 ";
            if($project_notsame_id){
                $sql .= " and po_project_id not in ({$project_notsame_id}) ";
            }
            $sql .= $where;
            $sql .="  and project_level !=-1 order by po_project_id desc";

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
}
?>