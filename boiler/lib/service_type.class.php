<?php

/**
 * service_type.class.php  服务类型类
 *
 * @version       v0.03
 * @create time   2014/09/04
 * @update time   2016/02/18 2016/3/25
 * @author        dxl wzp jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Service_type {


    public function __construct()
    {

    }

    static public function add($attrs)
    {
        if (empty($attrs)) {
            throw new Exception('参数不能为空', 102);
        }
        $id = Table_service_type::add($attrs);

        return $id;
    }
    static public function getInfoById($id)
    {
        return Table_service_type::getInfoById($id);
    }

    static public function update($id, $attrs)
    {
        return Table_service_type::update($id, $attrs);
    }

    static public function dels($id)
    {
        return Table_service_type::dels($id);
    }

    static public function getList($params = array())
    {
        return Table_service_type::getList($params);
    }

    static public function getCount($params = array())
    {
        return Table_service_type::getCount($params);
    }

    static public function getNameById($id)
    {
        $row = self::getInfoById($id);

        return isset($row['name']) ? $row['name'] : "";
    }

    static public function getInfoByName($name)
    {
        return Table_service_type::getInfoByName($name);
    }
}

?>