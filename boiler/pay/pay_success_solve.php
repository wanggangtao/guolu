<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2019/3/23
 * Time: 下午4:09
 */



try
{
    global $mypdo;
    $mypdo->pdo->beginTransaction();

    Boiler_repair_order::paySuccess($out_trade_no , $total_fee);


    $mypdo->pdo->commit();

}catch (MyException $e)
{
    $mypdo->pdo->rollBack();
}
