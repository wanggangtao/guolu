<?php

/***
 * Class Boiler_order_process
 * wanggangtao
 */

class Boiler_order_process
{

    public function __construct()
    {

    }

    /****
     * @param $id
     * @return array|mixed|string
     * @throws MyException
     * 添加订单的重派原因
     * 王刚涛
     */
    static public function reset_reason($order_id, $person_reason,$operation, $order_status, $service_person, $person_phone)
    {
        $attr = array(
            'order_id' => $order_id,
            'addtime' => time(),
            'operation' => $operation,
            'order_status' => $order_status,
            'service_person' => $service_person,
            'person_phone' => $person_phone,
            'person_reason' => $person_reason
        );
        $rs = Table_order_process::reset_reason($attr);
        return $rs;
    }

    static public function accept_orders($order_id, $operation, $order_status, $service_person, $person_phone, $person_reason)
    {
        $Table_order_process = new Table_order_process();
        $attr = array(
            'order_id' => $order_id,
            'addtime' => time(),
            'operation' => $operation,
            'order_status' => $order_status,
            'service_person' => $service_person,
            'person_phone' => $person_phone,
            'person_reason' => $person_reason
        );
//        var_dump($attr);
        $rs = $Table_order_process->accept_orders($attr);
//        var_dump($rs);
         return $rs;

    }




        /**
     * @param $id
     * @param $attrs
     * @return mixed
     */
    static public function getInfoById($id){
        return Table_order_process::getInfoById($id);
    }



    /****
     * 添加
     * sxx
     */


    static public function add($order_id, $operation, $order_status, $service_person, $person_phone, $person_reason)
    {

        $Table_order_process = new Table_order_process();
        $attr = array(
            'order_id' => $order_id,
            'addtime' => time(),
            'operation' => $operation,
            'order_status' => $order_status,
            'service_person' => $service_person,
            'person_phone' => $person_phone,
            'person_reason' => $person_reason
        );


        $rs = $Table_order_process->add($attr);

        if ($rs > 0) {
            $msg = '成功添加';
            return action_msg($msg, 1);
        } else {
            throw new MyException('操作失败', 106);
        }


    }

    public static function getCount($id){
        return Table_order_process::getCount($id);
    }
}
?>
