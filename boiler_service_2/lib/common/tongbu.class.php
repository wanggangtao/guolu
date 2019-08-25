<?php
/**
 *
 *  几个服务器之间同步文件
 * Created by PhpStorm.
 * User: tangqiang
 * Date: 17/8/24
 * Time: 下午3:12
 */

class tongbu
{




    static public function runSync($savepath,$f_path)
    {

        //同步文件
        global $MINXIN_FILE_PATH;
        $data = array("destPath"=>$savepath);
        Curl::curlFile($MINXIN_FILE_PATH."api/syncManage/syncFile.php",$f_path,$data);

    }

}