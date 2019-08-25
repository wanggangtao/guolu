<?php

/***
 * Class Boiler_repair_order
 * wanggangtao
 */

class Boiler_repair_parts
{

    public function __construct()
    {

    }
    static public function getrepair_part($id)
    {
        $r= Table_repair_order_parts::getrepair_part($id);
//        print_r($r);
        return $r;
    }
    static public function master_submit($id,$part,$part_num,$part_money){
        return Table_repair_order_parts::master_submit($id,$part,$part_num,$part_money);
    }

}


?>
