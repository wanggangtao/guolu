<?php
/**
 * Created by PhpStorm.
 * User: tangqiang
 * Date: 17/8/3
 * Time: 上午11:12
 */


class weixin_log{


    const ORDER_LOG = 1;
    const BACK_LOG = 2;

    static public function add($attrs)
    {
        /**
         * 加一些处理
         */

        return Table_weixin_log::add($attrs);

    }

    static public function update($id,$attrs)
    {
        /**
         * 加一些处理
         */

        return Table_weixin_log::update($id,$attrs);

    }

    static public function getList()
    {
        return Table_weixin_log::getList();
    }

    static public function getCount()
    {
        return Table_weixin_log::getCount();
    }

}
