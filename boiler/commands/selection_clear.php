<?php
require_once ("../init.php");


try{

    $needinfos = selection_history::getListByStatus();

    if(!empty($needinfos)) {

        foreach ($needinfos as $needinfo) {

            $historyid = $needinfo?$needinfo['id']:'';
            $startdate=date('Y-m-d',$needinfo['addtime']);
            $enddate=date('Y-m-d',time());
            $date=floor((strtotime($enddate)-strtotime($startdate))/86400);

            if($date >= 2) {
                Selection_history::del($historyid);
                Selection_fuji::delByHistoryId($historyid);
                Selection_heating_attr::delByHistoryId($historyid);
                Selection_hotwater_attr::delByHistoryId($historyid);
                Selection_plan::delByHistoryId($historyid);
                Selection_plan_front::delByHistoryId($historyid);
            }
        }
    }
}catch(MyException $e) {
    echo $e->jsonMsg();
}
?>