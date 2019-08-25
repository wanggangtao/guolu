<?php

/**
 * user.class.php 用户类
 *
 * @version       v0.01
 * @create time   2018/6/27
 * @update time   2018/6/27
 * @author        dlk
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class User {


    const NORMAL = 1;
    const AB_NORMAL = 2;

    public $id = 0;                //用户ID
    public $account = '';          //用户账号

    public function __construct($id = 0) {
        if(!empty($id)) {
            $user = self::getInfoById($id);
            if($user){
                $this->id      = $user['id'];
                $this->account = $user['account'];
            }else{
                throw new MyException('用户不存在', 902);
            }
        }
    }

    /**
     * User::login() 用户登录
     *
     * @param string  $account   账号
     * @param string  $password  密码
     * @param integer $cookie
     *
     * @return
     */
    public function login($account, $password, $cookie = 0){

        if(empty($account))throw new MyException('账号不能为空', 101);
        if(empty($password))throw new MyException('密码不能为空', 102);

        //检查账号
        $userinfo = Table_user::getInfoByAccount($account);
        if($userinfo == 0) {
            //不让用户准确知道是账号错误
            throw new MyException('账号或密码错误', 104);
        }

        if($userinfo["status"] !=self::NORMAL)
        {
            throw new MyException("用户状态不对,不允许登录!",106);
        }

        //验证密码
        $password = self::buildPassword($password, $userinfo['salt']);
        if($password[0] == $userinfo['password']){
            //登录成功
            $this->id         = $userinfo['id'];
            $this->account    = $userinfo['account'];

            //设置cookie;
            if($cookie) $this->buildCookie();

            //设置session
            self::setSession(1, $this->id);

            return action_msg('登录成功', 1);//登陆成功
        }else{
            throw new MyException('账号或密码错误', 104);
        }
    }

    /**
     * User::buildCookie()   设置登陆cookie
     *
     * @return void
     */
    private function buildCookie(){
        global $cookie_USERID, $cookie_USERCODE;

        $cookie_time = time()+(3600*24*7);//7天

        setcookie($cookie_USERID, $this->id, $cookie_time);
        setcookie($cookie_USERCODE, self::getCookieCode($this->id, $this->account), $cookie_time);
    }

    //消除cookie
    static private function rebuildCookie(){
        global $cookie_USERID, $cookie_USERCODE;

        setcookie($cookie_USERID, '', time()-3600);
        setcookie($cookie_USERCODE, '', time()-3600);
    }

    //生成cookie校验码
    static private function getCookieCode($id = 0, $account = ''){
        if(empty($id))throw new MyException('ID不能为空', 101);
        if(empty($account))throw new MyException('账号不能为空', 102);

        return md5(md5($account).md5($id));//校验码算法
    }
    /**
     * User::setSession()   设置登陆Session
     *
     * @param $type  1--记录Session  2--清除记录
     * @return void
     */
    static private function setSession($type, $id = 0){
        global $session_USERID;

        if($type == 1){
            if(empty($id))throw new MyException('ID不能为空', 101);
            $_SESSION[$session_USERID]    = $id;
        }else{
            $_SESSION[$session_USERID]    = 0;
        }
    }
    /**
     * User::logout()  退出登录
     *
     * @return void
     */
    static public function logout(){

        self::setSession(2);
        self::rebuildCookie();

    }

    /**
     * User::checkLogin()  检查是否登录
     *
     * @return
     */
    static public function checkLogin(){
        global $session_USERID;
        global $cookie_USERID, $cookie_USERCODE;

        //是否存在session
        if(@$_SESSION[$session_USERID]){
            return true;
        }

        //不存在session则检查是否有cookie
        $cid   = isset($_COOKIE[$cookie_USERID])?$_COOKIE[$cookie_USERID]:null;
        if(empty($cid)){
            return false;
        }

        //检查cookie数据是否对应，防止伪造
        $vcode = $_COOKIE[$cookie_USERCODE];
        $user = Table_user::getInfoById($cid);

        if(!$user) {
            //cookie数据不正确，清理掉
            self::rebuildCookie();
            return false;
        }

        $code = self::getCookieCode($cid, $user['account'], $user['group']);

        if($vcode != $code){
            //cookie数据不正确，清理掉
            self::rebuildCookie();
            return false;
        }

        //cookie数据正确，重写Session
        self::setSession(1, $cid);
        return true;
    }
    /**
     * User::getSession() 获得Session
     *
     * @return
     */
    static public function getSession(){
        global $session_USERID;

        return $_SESSION[$session_USERID];
    }

    /**
     * User::add() 添加
     * @param $attrs
     * @return mixed
     */
    static public function add($attrs){
        if(empty($attrs)) throw new Exception('参数不能为空', 102);
        //生成密码
        $password = self::buildPassword($attrs['password']);
        $attrs['password'] = $password[0];
        $attrs['salt'] = $password[1];
        $id =  Table_user::add($attrs);

        return $id;
    }

    /**
     * User::getInfoById() 根据ID获取详情
     * @param $id
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_user::getInfoById($id);
    }

    /**
     * User::getInfoByAccount() 根据账号获取详情
     * @param string $acount 账号
     * @return mixed
     */
    static public function getInfoByAccount($account){
        return Table_user::getInfoByAccount($account);
    }

    /**
     * User::getInfoByParentid() 根据父类ID获取详情
     * @param $parentid  父类ID
     * @return mixed
     */
    static public function getInfoByParentid($parentid,$role=''){
        return Table_user::getInfoByParentid($parentid,$role);
    }

    /**
     * User::getInfoByDepartment() 根据部门ID获取详情
     * @param $departmentid  部门ID
     * @return mixed
     */
    static public function getInfoByDepartment($departmentid){
        return Table_user::getInfoByDepartment($departmentid);
    }

    /**
     * User::getInfoByDepartment() 根据部门ID获取详情
     * @param $departmentid  部门ID
     * @return mixed
     */
    static public function getInfoForParent($departmentid, $role){
        return Table_user::getInfoForParent($departmentid, $role);
    }

    /**
     * User::getInfoByRole() 根据角色ID获取详情
     * @param $roleid  角色ID
     * @return mixed
     */
    static public function getInfoByRole($roleid){
        return Table_user::getInfoByRole($roleid);
    }

    static public function getRole(){
        return Table_user::getRole();
    }
    /**
     * User::update() 修改
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function update($id, $attrs){
        return Table_user::update($id, $attrs);
    }

    /**
     * User::del() 删除
     * @param $id
     * @return mixed
     */
    static public function del($id){
        return Table_user::del($id);
    }

    /**
     * User::delByParentid() 根据父类ID删除数据
     * @param $parentid  父类别ID
     * @return mixed
     */
    static public function delByParentid($parentid){
        return Table_user::delByParentid($parentid);
    }

    /**
     * User::getPageList() 根据条件列出所有信息
     * @param number $page             起始页
     * @param number $pagesize         页面大小
     * @param number $count            0数量1列表
     * @param string $name             姓名
     * @param number $status           状态
     * @param number $department       部门
     * @param number $role             角色
     * @return mixed
     */
    static public function getPageList($page, $pageSize, $count, $name, $status, $department, $role){
        return Table_user::getPageList($page, $pageSize, $count, $name, $status, $department, $role);
    }

    /**
     * User::buildPassword()  生成密码
     *
     * @param string $pwd   原始密码
     * @param string $salt  密码Salt
     * @return
     */
    static public function buildPassword($pwd, $salt = ''){

        if(empty($pwd))throw new MyException('密码不能为空', 101);
        if(empty($salt)) $salt = randcode(10, 4);//生成Salt

        $pwd_new = md5(md5($pwd).$salt);//加密算法

        return array($pwd_new, $salt);
    }

    /**
     * User::resetPwd()  重置密码
     * @param integer  $id   用户ID
     * @param string  $newpass   新密码
     *
     * @return
     */
    static public function resetPwd($id, $newpass){

        if(empty($id))throw new MyException('ID不能为空', 101);
        if(empty($newpass))throw new MyException('新的密码不能为空', 102);

        if(ParamCheck::is_weakPwd($newpass)) throw new MyException('新密码太弱', 103);

        $pass = self::buildPassword($newpass);

        $attrs = array(
            "password"=>$pass[0],
            "salt"=>$pass[1],
            "lastupdate"=>time()
        );

        return Table_user::update($id, $attrs);
    }


    /**
     * 获取一个用户的下属人员
     * @param $id
     * @return mixed
     * @throws MyException
     */
    static public function getChild($id){

        if(empty($id))throw new MyException('ID不能为空', 101);


        return Table_user::getChild($id);
    }





    /*********************************************************************************************************************/
    /*
    *
    *手机端登录
    * @return
    */
    public function loginForMobile($account, $password, $cookie = 0){

        if(empty($account))throw new MyException('账号不能为空', 101);
        if(empty($password))throw new MyException('密码不能为空', 102);

        //检查账号
        $userinfo = Table_user::getInfoByAccount($account);
        if($userinfo == 0) {
            //不让用户准确知道是账号错误
            throw new MyException('账号或密码错误', 104);
        }

        if($userinfo["status"] !=self::NORMAL)
        {
            throw new MyException("用户状态不对,不允许登录!",106);
        }

        //验证密码
        $password = self::buildPassword($password, $userinfo['salt']);
        if($password[0] == $userinfo['password']){

            return $userinfo;
        }else{
            throw new MyException('账号或密码错误', 104);
        }
    }

    /**
     * @param $id
     * @return array
     * @throws MyException
     */
    static public function getRoleIdByDepartment($id,$role){

        if(empty($id))throw new MyException('ID不能为空', 101);


        return Table_user::getRoleIdByDepartment($id,$role);
    }

    static public function getIdByDepartment($id){

        if(empty($id))throw new MyException('ID不能为空', 101);


        return Table_user::getIdByDepartment($id);
    }


    static public function getUserIdByRole($id){

        if(empty($id))throw new MyException('ID不能为空', 101);


        return Table_user::getUserIdByRole($id);
    }

    static public function getRoleList(){

        return Table_user::getRoleList();
    }

    static public function getRoleInfoByDepartment($id,$role){

        return Table_user::getRoleInfoByDepartment($id,$role);
    }
}

?>