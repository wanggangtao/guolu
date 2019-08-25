<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/20
 * Time: 16:45
 */
class Case_tplcontent {

    //-----------------tplcontent_attrid---------------------
    const DICT_CONTENT_ATTRID = 1;  //tplContent_content关联Dict表中的dict_id
    const OTHER_INFO_ATTRID = 6;  //其他问题详情
    const TEMOLET_INFO_ATTRID = 7;  //模板详情
    const COMPANY_INTRO_ATTRID = 8;  //企业简介
    const COMPANY_COMPETENCY_ATTRID = 9;   //企业资质
    const VENDER_INTRO_ATTRID = 11;  //工厂介绍
    const VENDER_COMPETENCY_ATTRID = 12;  //工厂资质
    const QUOTE_PRICE_ATTRID = 13;  //报价方案
    const FACTORY_USERLIST_ATTRID = 14;  //厂家用户名单
    const COMPANY_USERLIST_ATTRID = 15;  //公司用户名单
    const AFTERSALE_INTRO_ATTRID = 16;  //售后介绍
    const AFTERSALE_COMPETENCY_ATTTRID = 17;  //售后资质
    const FACTORY_USERLIST_NAME_ATTRID = 20;  //厂家用户名称
    const COMPANY_USERLIST_NAME_ATTRID = 23;  //公司用户名称
    const VERSION_CONTENT_ATTTRID = 31;  //规格参数

    //------注：开发人员在使用中若未找到某常量设置，可在此更新添加

    public function __construct() {

    }

    static public function getInfoByTplid($attrid){
        return Table_case_tplcontent::getInfoByTplid($attrid);
    }

    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_case_tplcontent::add($attrs);

        return $id;
    }



    static public function updateByTplid($tplid, $attrs){
        return Table_case_tplcontent::updateByTplid($tplid, $attrs);
    }

    static public function updateByAttrid($attrid, $attrs){
        return Table_case_tplcontent::updateByAttrid($attrid, $attrs);
    }

    static public function updateByAttridandtplid($attrid, $tplid, $attrs){
        return Table_case_tplcontent::updateByAttridandtplid($attrid, $tplid, $attrs);
    }

    static public function delByTplid($tplid){
        return Table_case_tplcontent::delByTplid($tplid);
    }

    static public function delByAttrid($attrid){
        return Table_case_tplcontent::delByAttrid($attrid);
    }

    static public function getInfoByAttridAndTplid($attrid, $tplid){
        return Table_case_tplcontent::getInfoByAttridAndTplid($attrid,$tplid);
    }

    static public function getListByTplid($tplid){
        return Table_case_tplcontent::getListByTplid($tplid);
    }
    //根据tplcontentId获取内容
    static public function getContentByTplcontentId($tplcontentId){
        return Table_case_tplcontent::getContentByTplcontentId($tplcontentId);
    }
    //获取content=dict_id,attr_id=1的tplContent
    static public function getListByDictId($content_id){
        return Table_case_tplcontent::getListByDictId($content_id);
    }
}