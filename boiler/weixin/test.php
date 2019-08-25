<?php
/**
 * Created by PhpStorm.
 * User: mamba
 * Date: 2019/8/20
 * Time: 16:04
 */
require_once "admin_init.php";
$name='A配件';
$num=2;
var_dump($name);
var_dump($num);
$we=Repair_parts::part_num($name,$num);
var_dump($we);