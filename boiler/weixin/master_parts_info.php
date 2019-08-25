<?php
/**
 * Created by PhpStorm.
 * User: TF
 * Date: 2019/8/18
 * Time: 16:32
 * 零件的详情
 */
require_once "admin_init.php";
$part_list=Repair_parts::getList();
var_dump($part_list);