<?php
/**
 * Created by PhpStorm.
 * User: tianyi
 * Date: 2018/10/21
 * Time: 13:42
 */
class Case_tpl {

    //-----------------------tpl_attrid--------------------
    const COMPANY_ATTRID = 1;  //企业
    const VENDER_ATTRID = 2;   //厂家
    const BOILER_USER_ATTRID = 20;  //锅炉用户
    const KANGDA_USER_ATTRID = 23;  //康达用户
    const AFTERSALE_ATTRID = 5;   //售后
    const OTHER_ATTRID = 6;  //其他

    //------注：开发人员在使用中若未找到某常量设置，可在此更新添加


    public function __construct() {

    }

    /**
     * case_tpl::getListByPid() 根据PID获取属性列表
     * @param $id
     * @return mixed
     */
    static public function getListByAttrid($pid,$page, $pageSize, $count){
        return Table_case_tpl::getListByAttrid($pid,$page, $pageSize, $count);
    }
    static public function getListByAttridandSel($pid,$sel_vender,$sel_usertype,$sel_userstate,$page, $pageSize, $count){
        return Table_case_tpl::getListByAttridandSel($pid,$sel_vender,$sel_usertype,$sel_userstate,$page, $pageSize, $count);
    }

    /**
     * 根据检索条件数组查询列表
     * @param array $params
     * @return array
     * @throws Exception
     */
    static public function getListBySelect($params = array()){
        return Table_case_tpl::getListBySelect($params);
    }

    static public function getInfoById($id){
        return Table_case_tpl::getInfoById($id);
    }

    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);

        $id =  Table_case_tpl::add($attrs);

        return $id;
    }

    static public function update($id, $attrs){
        return Table_case_tpl::update($id, $attrs);
    }

    static public function del($id){
        return Table_case_tpl::del($id);
    }

    /**
     * 根据不同attrid生成所属类型的唯一标记码
     * @param $attrs
     * @return mixed
     * @throws Exception
     */
    static public function createCode($attrid){
        //获取当前所属类型的最新ID
        $lastId = (Table_case_tpl::getLastId())+1;
        $code = null;
        switch ($attrid){
            case 1:
                $code = "company_".$lastId;
                break;
            case 2:
                $code = "factory_".$lastId;
                break;
            case 5:
                $code = "afterSale_".$lastId;
                break;
            case 6:
                $code = "otherParameter_".$lastId;
                break;
            case 13:
                $code = "quote_".$lastId;
                break;
            case 20:
                $code = "user_".$lastId;
                break;
        }
        return $code;
    }
}