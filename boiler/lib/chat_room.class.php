<?php

/**
 * Class Chat_room
 */
class Chat_room {

    const NORMAL = 1;  //正常状态
    const DELETE = -1;  //删除

    public function __construct() {

    }

    /**
     * @param $attrs
     * @return mixed
     * @throws Exception
     * zx
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);

        if(!empty($attrs["name"])&&empty($attrs["first"]))
        {
            $attrs["first"] = getFirstCharter($attrs["name"]);
        }

        $id =  Table_chat_room::add($attrs);

        return $id;
    }

    /**
     * @param $id
     * @param $attrs
     * @return mixed
     * zx
     */
    static public function update($id, $attrs){
        return Table_chat_room::update($id, $attrs);
    }


    static public function getInfoByProject($projectId)
    {

        return Table_chat_room::getInfoByProject($projectId);
    }

    /**
     * 根据参数获取room列表
     * @param $attrs
     * @return mixed
     */
    static public function getInfoByAttrs($attrs=array(),$page=0,$pageSize=0)
    {

        return Table_chat_room::getInfoByAttrs($attrs,$page,$pageSize);
    }



    /**
     * 根据参数获取room列表
     * @param $attrs
     * @return mixed
     */
    static public function getListByUser($uid,$attrs,$page,$pageSize)
    {

        return Table_chat_room::getListByUser($uid,$attrs,$page,$pageSize);
    }


    /**
     * @param $uid
     * @param $attrs
     * @param $page
     * @param $pageSize
     * @return array
     */
    static public function getListByUd($id,$uid,$attrs,$page,$pageSize)
    {

        return Table_chat_room::getListByUd($id,$uid,$attrs,$page,$pageSize);
    }


    /**
     * 根据ID获取info
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_chat_room::getInfoById($id);
    }

    static public function getFirstCharter($str)
    {
        if (empty($str)) {
            return '';
        }

        $fchar = ord($str{0});

        if ($fchar >= ord('A') && $fchar <= ord('z'))
            return strtoupper($str{0});

        $s1 = iconv('UTF-8', 'gb2312', $str);

        $s2 = iconv('gb2312', 'UTF-8', $s1);

        $s = $s2 == $str ? $s1 : $str;

        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;

        if ($asc >= -20319 && $asc <= -20284)
            return 'A';

        if ($asc >= -20283 && $asc <= -19776)
            return 'B';

        if ($asc >= -19775 && $asc <= -19219)
            return 'C';

        if ($asc >= -19218 && $asc <= -18711)
            return 'D';

        if ($asc >= -18710 && $asc <= -18527)
            return 'E';

        if ($asc >= -18526 && $asc <= -18240)
            return 'F';

        if ($asc >= -18239 && $asc <= -17923)
            return 'G';

        if ($asc >= -17922 && $asc <= -17418)
            return 'H';

        if ($asc >= -17417 && $asc <= -16475)
            return 'J';

        if ($asc >= -16474 && $asc <= -16213)
            return 'K';

        if ($asc >= -16212 && $asc <= -15641)
            return 'L';

        if ($asc >= -15640 && $asc <= -15166)
            return 'M';

        if ($asc >= -15165 && $asc <= -14923)
            return 'N';

        if ($asc >= -14922 && $asc <= -14915)
            return 'O';

        if ($asc >= -14914 && $asc <= -14631)
            return 'P';

        if ($asc >= -14630 && $asc <= -14150)
            return 'Q';

        if ($asc >= -14149 && $asc <= -14091)
            return 'R';

        if ($asc >= -14090 && $asc <= -13319)
            return 'S';

        if ($asc >= -13318 && $asc <= -12839)
            return 'T';

        if ($asc >= -12838 && $asc <= -12557)
            return 'W';

        if ($asc >= -12556 && $asc <= -11848)
            return 'X';

        if ($asc >= -11847 && $asc <= -11056)
            return 'Y';

        if ($asc >= -11055 && $asc <= -10247)
            return 'Z';

        return null;

    }

    static public function getRoomIdByProject($projectId)
    {

        return Table_chat_room::getRoomIdByProject($projectId);
    }


    static public function AddRoomByProject($projectid,$projectinfo=array())
    {

        $user_id = array();
        if(empty($projectinfo)){
            $projectinfo = Project::getInfoById($projectid);
        }
        $user_info = User::getInfoById($projectinfo['user']);
        if (!empty($user_info['parent'])){
            array_push($user_id,$projectinfo['user'],$user_info['parent']);
        }else{
            array_push($user_id,$projectinfo['user']);
        }
        if(!empty($user_info['department'])){
            $see_man = User::getRoleIdByDepartment($user_info['department'],4);
            $user_id = array_merge($user_id,$see_man);
        }
        $manage_id = User::getUserIdByRole(3);
        $user_id = array_merge($user_id,$manage_id);
        $user_id = array_unique($user_id);
        $room_attr = array(
            "name"=>$projectinfo['name'].'交流群',
            "first"=>Chat_room::getFirstCharter($projectinfo['name']),
            "principal_uid"=>$projectinfo['user'],
            "principal_uname"=>$user_info['name'],
            "project"=>$projectinfo['id'],
            "addtime"=>time(),
        );
        $room_id = Chat_room::add($room_attr);

        foreach ($user_id as $key=>$val){
            $config_attr = array(
                "room_id"=>$room_id,
                "uid"=>$val,
                "addtime"=>time()
            );
            Chat_room_msg_config::add($config_attr);
        }

        return $room_id;

    }

    static public function AddRoomByUid($Uid)
    {

        $user_id = array();
        $user_info = User::getInfoById($Uid);
        if (!empty($user_info['parent'])){
            array_push($user_id,$Uid,$user_info['parent']);
        }else{
            array_push($user_id,$Uid);
        }
        if(!empty($user_info['department'])){
            $see_man = User::getRoleIdByDepartment($user_info['department'],4);
            $user_id = array_merge($user_id,$see_man);
        }
        $manage_id = User::getUserIdByRole(3);
        $user_id = array_merge($user_id,$manage_id);
        $user_id = array_unique($user_id);
        $room_attr = array(
            "name"=>$user_info['name'].'工作汇报群',
            "first"=>Chat_room::getFirstCharter($user_info['name']),
            "principal_uid"=>$Uid,
            "type"=>2,
            "principal_uname"=>$user_info['name'],
            "addtime"=>time(),
        );
        $room_id = Chat_room::add($room_attr);

        foreach ($user_id as $key=>$val){

            $config_attr = array(
                "room_id"=>$room_id,
                "uid"=>$val,
                "addtime"=>time()
            );
            Chat_room_msg_config::add($config_attr);
        }

        return $room_id;

    }

    static public function getList()
    {

        return Table_chat_room::getList();
    }


    static public function getRoomList(){

        return Table_chat_room::getRoomList();
    }

    static public function getListByUId($id){
        return Table_chat_room::getListByUId($id);
    }

    static public function getRoomByUId($id){
        return Table_chat_room::getRoomByUId($id);
    }
}