<?php

/**
 * api.class.php 接口类
 *
 * @version       v0.02
 * @create time   2014/7/24
 * @update time   2016/5/22
 * @author        jt
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

class API {

    const SUCCESS = 200;
    const SUCCESS_MSG= "success";


    //API编号，数字
	public $code;

	//API来源
	public $source = 0;

	//API 某一天，时间戳
	public $day;

	//发生错误
	public $error = false;

	//是否统计错误调用
	public $errcount = false;

	public function __construct($code, $errcount = true) {

		$this->code = $code;
		$this->day = strtotime(date('Y-m-d'));
		if($errcount) $this->errcount = true;

	}
	
	//检查IP白名单
	public function checkIP(){
		global $Array_API_IP_WhiteList;
		$ip = getIP();//This Function in lib/function_common@WiiPHP
		if(!in_array($ip, $Array_API_IP_WhiteList)) $this->ApiError('999', 'IP不在白名单');
	}
	
	//发生错误
	public function ApiError($errcode, $errmsg){
		$this->error = true;
		
		//统计
		if($this->errcount) $this->apicount();

		$err = array();
		$err['code'] = $errcode;
        $err['message'] = $errmsg;
        $err['data'] = null;
		$err_json = json_encode_cn($err);
		echo $err_json;

		exit();//发生错误直接退出
	}

	//API调用统计
	public function apicount(){
		
		if($this->is_newday()){
			$this->count_newday();
		}else{
			$this->count_update();
		}
		
	}
	
	//写入统计数据
	private function count_newday(){

		if($this->error) 
			$errcount = 1;
		else
			$errcount = 0;

		Table_apicount::add($this->code, $this->source, $this->day, $errcount);
	}
	
	//更新统计数据
	private function count_update(){

		if($this->error) 
			$errcount = 1;
		else
			$errcount = 0;

		Table_apicount::updateCount($this->code, $this->source, $this->day, $errcount);
	}
	
	//判断是否是新的一天
	private function is_newday(){
		
		$code   = $this->code;
		$source = $this->source;
		$day    = $this->day;

		$r = Table_apicount::getInfo($code, $source, $day);
		if($r){
			return false;
		}else{
			return true;
		}
		
	}

	//API 设置来源
	public function setsource($source){
		$this->source = $source;
	}	
}
?>