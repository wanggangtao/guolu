<?php

/**
 * baseconfig.class.php 悬赏类
 *
 * @version       v0.02
 * @create time   2017/8/17
 * @update time
 * @author        xp
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
class Baseconfig
{

    const MINXIN_STATUS='minxin_status';
    const MINXIN_CONTENT_MANAGE='minxin_content_manage';

    const CFG_WEIXIN_APPID='cfg_weixin_appid';
    const CFG_WEIXIN_APPKEY='cfg_weixin_appkey';
    const MINXIN_SHADOW_SWITCH='minxin_shadow_switch';



    public $id = 0;           //ID

    public function __construct($id = 0)
    {
        if (!empty($id)) {
            $info = self::getInfoById($id);
            if ($info) {
                $this->id = $info['id'];
            } else {
                throw new MyException('数据不存在', 902);
            }
        }
    }

    /**
     * 根据id获取详情
     * @param $id
     * @return int|mixed
     * @throws MyException
     */
    static public function getInfoById($id)
    {
        if (empty($id)) throw new MyException('ID不能为空', 101);

        return Table_baseconfig::getInfoById($id);
    }

    /**
     * 根据id获取详情
     * @param $key
     * @return int|mixed
     * @throws MyException
     */
    static public function getInfoByKey($key)
    {
        if (empty($key)) throw new MyException('key不能为空', 101);

        return Table_baseconfig::getInfoByKey($key);
    }


    /**
     * 根据id获取详情
     * @param $key
     * @return int|mixed
     * @throws MyException
     */
    static public function getInfoByArr($keyArr)
    {
        if (empty($keyArr)) throw new MyException('key不能为空', 101);

        return Table_baseconfig::getInfoByArr($keyArr);
    }

    /**
     * 添加
     * @param $key
     * @param $value
     * @param $name
     * @return mixed
     */
    static public function add($key, $value, $name)
    {

        return Table_baseconfig::add($key, $value, $name);
    }

    /**
     * 更新
     * @param $id
     * @param array $update_info
     * @return mixed
     */
    static public function update($id,  $update_info)
    {

        return Table_baseconfig::update($id, $update_info);
    }


    /**
     * 更新
     * @param $id
     * @param array $update_info
     * @return mixed
     */
    static public function updateByKey($key,  $update_info)
    {

        return Table_baseconfig::updateByKey($key, $update_info);
    }

    static public function edit_minxinswitch($minxinswitch){
        return Table_baseconfig::edit_minxinswitch($minxinswitch);
    }
    static public function edit_minxin_shadow_switch($minxinswitch){
        return Table_baseconfig::edit_minxin_shadow_switch($minxinswitch);
    }


    static public function getStatus(){
        return Table_baseconfig::getStatus();
    }
}

?>