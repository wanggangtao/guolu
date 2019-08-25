<?php

/**
 * project.class.php 项目类
 *
 * @version       v0.01
 * @create time   2018/6/24
 * @update time   2018/6/24
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Project {


    //星级状态
    const UN_REPORT = 0;//未报备
    const LEVEL_ONE = 1;//一颗星
    const LEVEL_OTWO = 2;//一颗星
    const LEVEL_THREE = 3;//一颗星
    const LEVEL_FOUR = 4;//一颗星
    const LEVEL_FIVE = 5;//一颗星


    //提交状态

    const UN_SUBMIT = 1;
    const SUBMITED = 2;
    const CHECKED = 3;
    const REJECTED = 4;


    //项目终止状态
    const DOING = 0;//正常
    const STOP = 1;//终止



    static public function Init() {
        $r = array();
        $r['id']            = '';
        $r['code']          = '';
        $r['name']          = '';
        $r['addtime']       = '';
        $r['level']         = 0;
        $r['status']        = 0;
        $r['one_status']    = 0;
        $r['two_status']    = 0;
        $r['three_status']  = 0;
        $r['four_status']   = 0;
        $r['type']          = 0;
        $r['lastupdate']    = '';
        $r['user']          = '';
        $r['stop_flag']     = 0;
        $r['stopreason']    = '';
        $r['summarize']     = '';
        $r['detail']        = '';
        $r['reviewopinion'] = '';
        $r['bonus']         = 0.00;
        $r['notice_one']    = '';
        $r['notice_two']    = '';
        $r['notice_three']  = '';
        $r['export_flag']  = 1;
        $r['bonus_stage']  = '';

        return $r;
    }

    /**
     * Project::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        $id =  Table_project::add($attrs);
        return $id;
    }

    /**
     * Project::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_project::getInfoById($id);
    }

    /**
     * Project::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_project::update($id, $attrs);
    }

    /**
     * Project::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_project::del($id);
    }

    /**
     * Project::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $name             名称
     * @param number $type             类型
     * @param number $status           名称
     * @param number $level            阶段
     * @param number $user             项目所属人
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $name, $type, $status, $level, $user,$startTime=0,$endTime=0){
        return Table_project::getPageList($page, $pageSize, $count, $name, $type, $status, $level, $user,$startTime,$endTime);
    }

    /**
     * Project::getPageReviewList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $name             名称
     * @param number $type             类型
     * @param number $status           名称
     * @param number $level            阶段
     * @param number $user             项目所属人
     * @return mixed
     */
    static public function getPageReviewList($page, $pageSize, $count, $name, $type, $status, $level, $user, $username,$startTime=0,$endTime=0){
        return Table_project::getPageReviewList($page, $pageSize, $count, $name, $type, $status, $level, $user, $username,$startTime,$endTime);
    }

    /**
     * Project::getPageSeclectList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $name             名称
     * @param number $type             类型
     * @param number $status           名称
     * @param number $level            阶段
     * @param number $user             项目所属人
     * @return mixed
     */
    static public function getPageSeclectList($page, $pageSize, $count, $name, $type, $status, $level, $user, $loginuser,$startTime=0,$endTime=0,$department=0){
        return Table_project::getPageSeclectList($page, $pageSize, $count, $name, $type, $status, $level, $user, $loginuser,$startTime,$endTime,$department);
    }

    /**
     * Project::getInfoByName() 根据ID获取详情
     * @param $name
     * @return mixed
     */
    static public function getInfoByName($name){
        return Table_project::getInfoByName($name);
    }

    /**
     * Project::getAllSendList() 获取详情
     * @param $name
     * @return mixed
     */
    static public function getAllSendList(){
        return Table_project::getAllSendList();
    }

    /************************************************app接口****************************************************/

    /**
     * 获取总数量
     * @param $userId
     * @return int
     */
    static public function getTotalCount($userId){

        return Table_project::getTotalCount($userId);

    }



    /**
     * 获取未报备数量
     * @param $userId
     * @return int
     */
    static public function getNotReportCount($userId){

        return Table_project::getNotReportCount($userId);

    }


    /**
     * 获取进行中的数量
     * @param $userId
     * @return int
     */
    static public function getDoingCount($userId){

        return Table_project::getDoingCount($userId);

    }


    /**
     * 获取已终止数量
     * @param $userId
     * @return int
     */
    static public function getStopCount($userId){

        return Table_project::getStopCount($userId);

    }


    /**
     * 校验用户是否有权限查看项目
     */
    static public function checkProjectForUser($userId,$projectId){

        $projectInfo = Project::getInfoById($projectId);
        $userInfo = user::getInfoById($userId);
        if($userInfo['role'] == 3){
            return true;
        }

        if($userInfo['role'] == 4){
            $puserinfo = User::getInfoById($projectInfo['user']);
            if($userInfo['department'] == $puserinfo['department']){
                return true;
            }else{
                return false;
            }
        }

        if(empty($userInfo)) throw new MyException("用户不存在",101);

        $childInfo = user::getChild($userId);

        $childList = array();
        if(!empty($childInfo))
        {
            foreach ($childInfo as $child)
            {
                $childList[] = $child["id"];
            }
        }

        $childList[] = $userId;


        if(empty($projectInfo)) throw new MyException("项目不存在",102);

        if(in_array($projectInfo["user"],$childList))
        {
            return true;
        }
        else
        {
            false;
        }

    }

    static public function getReviewCount($userId,$role){

        return Table_project::getReviewCount($userId,$role);

    }

    static public function getReviewProjectCount($userId,$role){

        return Table_project::getReviewProjectCount($userId,$role);

    }


}
?>