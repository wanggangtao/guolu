<?php


$env = get_cfg_var('ENVIRONMENT');




if($env=='online')
{
    require_once ("config_online.inc.php");
}
else
{
    require_once ("config_develop.inc.php");
}


?>