<?php

/**
 * table_apicount.class.php API调用统计表
 *
 * @version       $Id$ v0.01
 * @createtime    2016/5/22
 * @updatetime    
 * @author        jt
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */

class Table_apicount extends Table{
	
	/**
	 * Table_apicount::struct()  数组转换
	 * 
	 * @param array $data
	 * 
	 * @return $r
	 */
	static protected function struct($data){
	   	$r = array();
     
        $r['id']      = $data['apicount_id'];
		$r['code']    = $data['apicount_code'];
		$r['source']  = $data['apicount_source'];
		$r['day']     = $data['apicount_day'];
		$r['count']   = $data['apicount_count'];
		$r['errcount']= $data['apicount_errcount'];

        return $r;
	}

	
    /**
	 * Table_apicount::add()
	 * 
	 * @param $code
	 * @param $source
	 * @param $day
	 * @param $count
	 * @param $errcount
	 * @return
	 */
	static public function add($code, $source, $day, $errcount){ 
        global $mypdo;

    	//写入数据库
		$param = array (
			'apicount_code'      => array('string', $code),
			'apicount_source'    => array('number', $source),
			'apicount_day'       => array('number', $day),
			'apicount_count'     => array('number', 1),
			'apicount_errcount'  => array('number', $errcount)
		);

		return $mypdo->sqlinsert($mypdo->prefix.'apicount', $param);
    }

	/**
	 * Table_apicount::getInfoByCSD() 查询数据
	 * 
	 * @param mixed $code
	 * @param mixed $source
	 * @param mixed $day
	 * @return
	 */
	static public function getInfo($code, $source, $day){
		global $mypdo;
        
		$code   = $mypdo->sql_check_input(array('string', $code));
		$source = $mypdo->sql_check_input(array('number', $source));
		$day    = $mypdo->sql_check_input(array('number', $day));
        
        $sql = "select * from ".$mypdo->prefix."apicount where apicount_code=$code and apicount_source=$source and apicount_day=$day limit 1";
        
        $rs = $mypdo->sqlQuery($sql);
		if($rs){
            $r = array();
            foreach($rs as $key => $val){
                $r[$key] = self::struct($val);
            }
            return $r[0];
        }else{
            return 0;
        }
	}


	/**
	 * Table_apicount::update() 更新统计
	 * 
	 * @param mixed $id
	 * @param mixed $name
     * @param int   $status
	 * @return
	 */
	static public function updateCount($code, $source, $day, $errcount){ 
        global $mypdo;

		$param = array (
			'apicount_count'      => array('expression', 'apicount_count+1'),
			'apicount_errcount'   => array('expression', 'apicount_errcount+'.$errcount)
		);
		$where = array (
			'apicount_code'      => array('string', $code),
			'apicount_source'    => array('number', $source),
			'apicount_day'       => array('number', $day)
		);

		return $mypdo->sqlupdate($mypdo->prefix.'apicount', $param, $where);
    }
}
?>