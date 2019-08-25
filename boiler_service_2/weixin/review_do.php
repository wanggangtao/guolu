<?php
/**
 * Created by PhpStorm.
 * User: TF
 * Date: 2019/8/13
 * Time: 15:02
 */
require_once "admin_init.php";
$act = safeCheck($_POST['act'], 0);//调试接口
//$act = safeCheck($_GET['act'], 0);//本地调试接口
switch($act) {
    case 'add_remark'://师傅端待支付的备注信息和支付方式的添加
        try {
            $order_id= safeCheck($_POST['order_id'],0);
            $pay_style = safeCheck($_POST['pay_style'], 0);
            $remark = safeCheck($_POST['remark'], 0);
            $rs = Boiler_repair_order::master_remark($order_id,$remark,$pay_style);//修改订单表的状态
            echo $rs;
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;
    case 'valid'://取消订单
        try {
            $order_id= safeCheck($_POST['order_id'],0);
            $reason = safeCheck($_POST['reason'], 0);
            $rs = Boiler_repair_order::valid_order($order_id,$reason);//修改订单表的状态
            echo $rs;
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;
    case 'review'://订单的评价
        $content = safeCheck($_POST['content'], 0);
        $id = safeCheck($_POST['id'], 0);
        $picture = safeCheck($_POST['picture'], 0);
        try {
            $rs = Boiler_repair_order::edit($id, $content,$picture);
            echo $rs;
        } catch (MyException $e) {
            echo $e->jsonMsg();

        }
        break;
    case 'del'://删除订单
    try {
        $id= safeCheck($_POST['id'], 1);
        $rs = Boiler_repair_order::del_order($id);
        echo $rs;
    } catch (MyException $e) {
        print_r("失败");
        echo $e->jsonMsg();
    }
    break;
    case 'accept'://接受订单
    try {
        $id= safeCheck($_POST['id'], 1);
        $r = Boiler_order_process::accept_orders($id,2,22);//流程表中只添加记录
        if ($r > 0) {
            $rs = Boiler_repair_order::accept_order($id);
        }
        echo $rs;
    } catch (MyException $e) {
        echo $e->jsonMsg();
    }
    break;
    case 'reset_reason'://重新派订单，添加原因
        try {
            $order_id= safeCheck($_POST['order_id'], 0);
            $reason = safeCheck($_POST['reason'], 0);
            $r = Boiler_order_process::reset_reason($order_id,$reason,8,12);//添加流程表
            if ($r >=0) {//测试的时候，受影响行数为零的时候
                $rs = Boiler_repair_order::reset_order($order_id);//修改订单表的状态
            }
            echo $rs;
        } catch (MyException $e) {
            echo $e->jsonMsg();
        }
        break;
    case 'submit'://订单的提交
        if(!empty($_POST['parts']));//接收数据
        {
            $parts=$_POST['parts'];
        }
        $hand_money=safeCheck($_POST['hand_money'],1);//接收数据
        $id = safeCheck($_POST['id'], 1);
        $resove_style = safeCheck($_POST['resove_style'], 1);
        $content = safeCheck($_POST['content'], 0);
        if(isset($_POST['picture']))
        {
            $picture = safeCheck($_POST['picture'], 0);
        }

        $period = safeCheck($_POST['period'], 0);
        $pay_style = safeCheck($_POST['pay_style'], 1);
//        $remark = safeCheck($_POST['remark'], 0);//可以为空
        if(empty($resove_style))
        {
            echo "解决途径不能为空";
            return false;
        }
        if(empty($content))
        {
            echo "维修情况不能为空";
            return false;
        }
        if(empty($period))
        {
            echo "保修期限不能为空";
            return false;
        }
        if(empty($hand_money))
        {
            echo "上门费用不能为空";
            return false;
        }
        if(empty($pay_style))
        {
            echo "支付方式不能为空";
            return false;
        }
        try {
            $count = count($parts);//零件的个数
            $part_sum_money=0;//零件的总价
            for ($i = 0; $i < $count; $i++)
            {   //在零件的总表中减去所用零件的个数
                $we=Repair_parts::part_num($parts[$i]["name"],$parts[$i]["num"]);//在零件表中减去所用的零件数目
                if($we>0)
                {
                    $part_sum_money =$part_sum_money+$parts[$i]["num"]*$parts[$i]["price"];//维修总的零件的费用
                    $rrs = Boiler_repair_parts::master_submit($id,$parts[$i]["name"],$parts[$i]["num"],$parts[$i]["price"]);//修改零件表,有多少零件，就添加多少条记录
                }
            }
            if($rrs>=0 && $we>=0 )
            {
                $rs = Boiler_repair_order::master_submit($id,$resove_style, $content,$part_sum_money,$picture, $hand_money,$pay_style);//修改订单表
                if($rs>=0)
                {
                    $orderList=Boiler_repair_order::getrepair_detail($id);//查出订单的详情
//                    var_dump($id);
//                    var_dump($orderList);
                    $code=$orderList["bar_code"];//获取产品的型号
                    $rrrs =  Product_info::master_submit($code,$period);//修改产品表的保质期
                }
            }
            echo $rrrs;//返回成功
        } catch (MyException $e) {
            print_r("2");
            echo $e->jsonMsg();
        }
        break;
}

