<?php



class User_account {

    public function __construct() {

    }

    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_user_account::add($attrs);

        return $id;
    }

    /**
     * Category::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_user_account::getInfoById($id);
    }
    static public function getInfoByPhone($phone){
        return Table_user_account::getInfoByPhone($phone);
    }

    /**
     * Category::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_user_account::update($id, $attrs);
    }

    /**
     * Category::del() 删除
     * @param $id
     * @return mixed
     */
    static public function dels($id){
        return Table_user_account::dels($id);
    }

    static public function getList($params=array()){
        return Table_user_account::getList($params);
    }
    static public function getCount($params=array()){
        return Table_user_account::getCount($params);
    }
    static public function getInfoByBarCode($barCode){
        return Table_user_account::getInfoByBarCode($barCode);
    }
    static public function getInfoByOpenid($openId){
        return Table_user_account::getInfoByOpenid($openId);

    }


    static public function login_out($openId){

        return Table_user_account::login_out($openId);
    }
    static public function getAllByOpenid($openId){
        return Table_user_account::getAllByOpenid($openId);

    }

    static public function updataBarCode($code){
        return Table_user_account::updataBarCode($code);
    }
    static public function getAvailabelCode($code = ""){
        return Table_user_account::getAvailabelCode($code);
    }
    static public function getNameById($id)
    {
        $row = self::getInfoById($id);

        return isset($row['name']) ? $row['name'] : "";
    }
    static public function getIdByLikeName($name){
        return Table_user_account::getIdByLikeName($name);
    }

    /**
     * Table_burner_attr::getInfoById() 根据ID获取详情
     *
     * @param Integer $burnerId  燃烧器ID
     *
     * @return
     */
    static public function getUserIdByCommunity($community_id){
        return Table_user_account::getUserIdByCommunity($community_id);
    }

}
?>