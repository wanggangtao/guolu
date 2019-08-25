<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2019/1/25
 * Time: 上午9:54
 */




$command = "cd /home/wwwroot/default/boiler_service;git pull origin master";


exec($command,$data);

print_r($data);