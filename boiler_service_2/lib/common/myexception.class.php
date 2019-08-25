<?php

/**
 * exception.class.php 错误类文件
 *
 * @version       v0.01
 * @create time   2016/3/26
 * @update time   
 * @author        jt
 * @copyright     Copyright (c) 芝麻开发 (http://www.zhimawork.com)
	

 */

class MyException extends Exception {

	public function jsonMsg(){

		$code = $this->getCode();
		$msg  = $this->getMessage();

		return action_msg($msg, $code, 1);
	}

}
?>