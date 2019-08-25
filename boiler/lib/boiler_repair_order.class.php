<?php

/***
 * Class Boiler_repair_order
 * wanggangtao
 */

class Boiler_repair_order{

    public function __construct()
    {

    }

    /***
     * @param $id
     * @param int $status
     * @return array
     * 王刚涛
     * 项目的用户端的待支付，已成功，等子模块
     */

    static public function getListrepair($id,$status=0,$child_status=0)
    {
                return Table_repair_order::getListrepair_order($id,$status,$child_status);
    }

    /***
     * @param $id
     * @return array|bool|mixed|string
     * @throws MyException
     * 订单失效
     */
    static public function valid_order($id,$reason)
    {
        $rs= Table_repair_order::valid_order($id,$reason);
        if($rs == 1){
            $msg = '订单取消成功!';
            return action_msg($msg, 1);
        }else{
            throw new MyException('订单取消失败', 102);
        }
    }

    /***
     * @param $id
     * @param int $status
     * @param int $child_status
     * @return mixed
     * 师傅端的待接单，已结单等
     */
    static public function getList_repair_master($id,$status = 0,$child_status=0)
    {
        return Table_repair_order::getList_repair_master($id,$status,$child_status);
    }

    /***
     * @param $id
     * @return array
     * wanggangtao
     * 订单的id查看订单的详情
     */
    static public function getrepair_detail($id)
    {
        $rs= Table_repair_order::getrepair_detail($id);
        return  $rs;
    }

    /***
     * @param $person
     * @return mixed
     * wanggnagtao
     * 根据项目的维修人员编号，查询维修人员的信息
     */

    static public function getrepair_person($person)
    {
        return Table_repair_person::getrepair_person($person);
    }
    static public function del_order($id)
    {
//        var_dump($id);
        $rs =Table_repair_order::del_order($id);
//       return $rs ;
        if($rs == 1){
            $msg = '订单('.$id.')删除成功!';
            return action_msg($msg, 1);
        }else{
            throw new MyException('删除失败', 102);
        }
    }
    /***
     * @param $person
     * @return mixed
     * 添加评价
     */
    static public function edit($id,$content,$picture)
    {
        $rs= Table_repair_order::edit($id, $content,$picture);
        if($rs == 1){
            $msg = '订单('.$id.')评价成功!';
            return action_msg($msg, 1);
        }else{
            throw new MyException('评价失败', 102);
        }
    }

    static public function accept_order($id)
    {
        $rs= Table_repair_order::accept_order($id);
        if($rs == 1){
            $msg = '接单('.$id.')成功!';
            return action_msg($msg, 1);
        }else{
            throw new MyException('接单失败', 102);
        }
    }
    /***
     *重新派单
     */
    static public function reset_order($id)
    {
        $rs= Table_repair_order::reset_order($id);
        if($rs >=0){//if($rs == 1){
            $msg = '申请重派('.$id.')成功!';
            return action_msg($msg, 1);
        }else{
            throw new MyException('申请失败', 102);
        }
    }

    /****
     * @param $id
     * @param $resove_style
     * @param $content
     * @param $part_money
     * @param $picture
     * @param $hand_money
     * @param $pay_style
     * @param $remark
     * @return mixed
     * 师傅端提交数据
     */
    static public function master_submit($id,$resove_style, $content,$part_sum_money,$picture, $hand_money,$pay_style)
    {
        return Table_repair_order::master_submit($id,$resove_style, $content,$part_sum_money,$picture, $hand_money,$pay_style);

    }

    /**
     * @return string 返回充值单号
     */
    static public function getChargeNum()
    {
        $orderNum = "WXD" . date("YmdHis") . randcode(2, 2);

        $len = 4;
        $i = 1;
        while (self::getInfoByOrderNum($orderNum)) {
            if ($i % 10 == 0) {
                $i = 0;
                $len++;
            }
            $orderNum = "WXD" . date("YmdHis") . randcode(2, 2);
            $i++;
        }

        return $orderNum;
    }


    static public function getInfoByOrderNum($orderNum)
    {
        return Table_repair_order::getInfoByOrderNum($orderNum);
    }
    static public function paySuccess($out_trade_no ,$total_fee){

        $order_info = self::getInfoByOrderNum($out_trade_no);

//        print_r($order_info);
//        exit();
        if(!empty($order_info)){
            $attrs =array(
                "status" => 3,
                "child_status" => 32,
                "repair_pay_style" => 1,
                "sum_money" => $total_fee
            );

            Table_repair_order::update($order_info['id'],$attrs);
           $rs =  Boiler_order_process::add($order_info['id'],5,23,"","","");

        }else{
            return 0;
        }

    }


}
?>
