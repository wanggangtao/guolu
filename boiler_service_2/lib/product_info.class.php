<?php

/**
 * category.class.php 产品类别类
 *
 * @version       v0.01
 * @create time   2018/5/27
 * @update time   2018/5/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Product_info {

    public function __construct() {

    }

    static public function getInfoBycode($bar_code){
        return Table_product_info::getInfoBycode($bar_code);
    }

    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_product_info::add($attrs);

        return $id;
    }

    /**
     * Category::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_product_info::getInfoById($id);
    }

    /**
     * Category::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_product_info::update($id, $attrs);
    }

    /**
     * Category::del() 删除
     * @param $id
     * @return mixed
     */
    static public function dels($id){
        return Table_product_info::dels($id);
    }

    static public function master_submit($code,$period){
        $rs=Table_product_info::master_submit($code,$period);
        if($rs >=0){
            $msg = '提交成功!';
            return action_msg($msg, 1);
        }else{
            throw new MyException('提交失败', 102);
        }

    }

    static public function getList($params=array()){
        return Table_product_info::getList($params);
    }

    static public function getCount($params=array()){
        return Table_product_info::getCount($params);
    }

    static public function getInfoByBarCode($info_code){
        return Table_product_info::getInfoByBarCode($info_code);
    }

    static public function getInfoLikeCode($code){
        return Table_product_info::getInfoLikeCode($code);
    }

    static public function updateByCode($id,$attrs){
        return Table_product_info::updateByCode($id,$attrs);
    }

    static public function updateByCommunity($id,$attrs){
        return Table_product_info::updateByCommunity($id,$attrs);
    }
}
?>