<?php

/**
 * code.class.php 产品类别类
 *
 * @version       v0.01
 * @create time   2018/7/7
 * @update time   2018/7/7
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Code {

    public function __construct() {

    }

    /**
     * Code::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_code::add($attrs);

        return $id;
    }

    /**
     * Code::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_code::getInfoById($id);
    }

    /**
     * Code::getInfoByTD() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoByTD($type, $day){
        return Table_code::getInfoByTD($type, $day);
    }
    /**
     * Code::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function updateCode($id, $type, $day, $codenum){
        return Table_code::updateCode($id, $type, $day, $codenum);
    }

    /**
     * Code::buildCode() 生成编号
     * @param number $periodid    期次ID
     * @return
     */
    static public function buildCode($type, $day){
        $info = self::getInfoByTD($type, $day);
        if(empty($info) && $type == 2){
            $daystr = date('ymd',time());
            $attrs = array(
                "type" => 2,
                "day"  => $daystr,
                "lasttime" => time(),
                "num"      => 1
            );
            $rs = self::add($attrs);
            if($rs > 0){
                return $daystr."01";
            }else{
                return "";
            }
        }else{
            $rs = Table_code::updateCode($info['id'], $info['type'], $info['day'], $info['num']);
            while ($rs == 0){
                $info = self::getInfoByTD($type, $day);
                $rs = self::updateCode($info['id'], $info['type'], $info['day'], $info['num']);
            }
            return $info['day'].str_pad($info['num'] + 1, 2, '0' ,STR_PAD_LEFT);
        }
    }
}
?>