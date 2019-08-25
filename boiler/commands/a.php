<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2018/12/1
 * Time: 下午5:15
 */


require_once ("../init.php");








exec("php {$FILE_PATH}commands/selection_plan_do.php",$result);


print_r($result);


