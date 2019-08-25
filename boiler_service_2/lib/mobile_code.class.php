<?php

/**
 * mobile_code.class.php
 *
 * @version       v0.01
 * @create time   2016/4/3/
 * @update time   
 * @author        TQ
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
class mobile_code
{

	const LimitTime = 180;
	/**
	 * register::__construct()   构造函数
	 * @return
	 */
	public function __construct()
	{

	}


	/**
	 * @param $mobile
	 * @param $code
	 * @return mixed
	 * @throws MyException
	 */
	static public function add($mobile,$code)
	{
		if (empty($mobile)) throw new MyException("k手机不能为空!", 101);
		$id = Table_mobile_code::add($mobile,$code);
		if ($id) {
			return $id;
		} else {
			throw new MyException('操作失败', 103);
		}
	}


	/**
	 * @param $id
	 * @param $code
	 * @return mixed
	 * @throws MyException
	 */
	static public function update($id,$code)
	{
		if (empty($id)) throw new MyException("id不能为空!", 101);

		$rs = Table_mobile_code::update($id, $code);
		if ($rs) {
			return $rs;
		} else {
			throw new MyException('操作失败', 103);
		}
	}


	/**
	 * @param $register_id
	 * @return array|mixed|string
	 * @throws MyException
	 */
	static public function del($id)
	{
		if (empty($id)) throw new MyException('注册ID不能为空', 101);

		$rs = Table_mobile_code::del($id);

		if ($rs == 1) {
			$msg = '删除注册记录成功!';

			return action_msg($msg, 1);//成功
		} else {
			throw new MyException('操作失败', 103);
		}
	}


	/**
	 * @param $id
	 * @return int|mixed
	 * @throws MyException
	 */
	static public function getInfoById($id)
	{
		if (empty($id)) throw new MyException('映射关系id不能为空', 101);

		return Table_mobile_code::getInfoById($id);
	}

	/**
	 * @param $key
	 * @return mixed
	 * @throws MyException
	 */
	static public function getInfoByMobile($mobile)
	{
		if (empty($mobile)) throw new MyException('手机不能为空', 101);

		return Table_mobile_code::getInfoByMobile($mobile);

	}


	/**
	 * 校验验证码
	 * @param $mobile
	 * @param $code
	 * @return bool
	 * @throws MyException
	 */
	static public function checkCode($mobile,$code)
	{
		if(empty($mobile)) throw new MyException("手机号不能为空!",101);
		if(empty($code)) throw new MyException("验证码不能为空!",102);

		$info = self::getInfoByMobile($mobile);
		if(empty($info)) throw new MyException("验证码验证失败!",103);

		if((time()-$info["lastupdate"])>self::LimitTime) throw new MyException("验证码已经失效!",104);

		if($info["value"]!= $code) throw new MyException("验证码错误!",105);

		return true;
	}

	/*
	 * 根据验证码发送的时间、手机号查询验证码总量
	 *
	 * */
	static public function getSearchCount($attrs)
	{
		return Table_mobile_code::getSearchCount($attrs);
	}

	/*
	 * 根据条件查询验证码列表
	 * */
	static public function search($attrs)
	{
		return Table_mobile_code::search($attrs);
	}

}
?>
