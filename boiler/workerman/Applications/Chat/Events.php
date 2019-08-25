<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

/**
 * 聊天主逻辑
 * 主要是处理 onMessage onClose 
 */


use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\DbConnection;



class Events
{



    public static $db = null;




    /**
     * 进程启动后初始化数据库连接
     */
    public static function onWorkerStart($worker)
    {


        self::$db = new DbConnection("47.96.24.215", 3306, "boiler", "*boiler#", "boiler");
    }


    /**
    * 有消息时
    * @param int $client_id
    * @param mixed $message
    */
   public static function onMessage($client_id, $message)
   {
        // debug
        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id session:".json_encode($_SESSION)." onMessage:".$message."\n";
        
        // 客户端传递的是json数据
        $message_data = json_decode($message, true);

        if(!$message_data)
        {
            return ;
        }
        
        // 根据类型执行不同的业务
        switch($message_data['type'])
        {
            // 客户端回应服务端的心跳
            case 'pong':
                return;
            // 客户端登录 message格式: {type:login, name:xx, room_id:1} ，添加到客户端，广播给所有客户端xx进入聊天室
            case 'login':
                // 判断是否有房间号
                if(!isset($message_data['room_id']))
                {
                    throw new \Exception("\$message_data['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                }
                
                // 把房间号昵称放到session中
                $room_id = $message_data['room_id'];
                $uid = $message_data['uid'];

                $client_name = htmlspecialchars($message_data['client_name']);
                $_SESSION['room_id'] = $room_id;
                $_SESSION['uid'] = $uid;

                $_SESSION['client_name'] = $client_name;

                Gateway::bindUid($client_id,$uid);

              
                // 获取房间内所有用户列表 
                $clients_list = Gateway::getClientSessionsByGroup($room_id);


                foreach($clients_list as $tmp_client_id=>$item)
                {



                    if($item["uid"]==$uid)
                    {
                        Gateway::leaveGroup($tmp_client_id, $room_id);
                        continue;
                    }

                    $clients_list[$tmp_client_id] = $item['client_name'];
                }
                $clients_list[$client_id] = $client_name;
                
                // 转播给当前房间的所有客户端，xx进入聊天室 message {type:login, client_id:xx, name:xx} 
                $new_message = array('type'=>$message_data['type'], 'client_id'=>$client_id, 'client_name'=>htmlspecialchars($client_name), 'time'=>date('Y-m-d H:i:s'));
                Gateway::sendToGroup($room_id, json_encode($new_message));
                Gateway::joinGroup($client_id, $room_id);
               
                // 给当前用户发送用户列表 
                $new_message['client_list'] = $clients_list;
                Gateway::sendToCurrentClient(json_encode($new_message));
                return;
                
            // 客户端发言 message: {type:say, to_client_id:xx, content:xx}
            case 'say':
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $client_name = $_SESSION['client_name'];
                
// 暂不支持私聊
//                if($message_data['to_client_id'] != 'all')
//                {
//                    $new_message = array(
//                        'type'=>'say',
//                        'from_client_id'=>$client_id,
//                        'from_client_name' =>$client_name,
//                        'to_client_id'=>$message_data['to_client_id'],
//                        'content'=>"<b>对你说: </b>".nl2br(htmlspecialchars($message_data['content'])),
//                        'time'=>date('Y-m-d H:i:s'),
//                    );
//                    Gateway::sendToClient($message_data['to_client_id'], json_encode($new_message));
//                    $new_message['content'] = "<b>你对".htmlspecialchars($message_data['to_client_name'])."说: </b>".nl2br(htmlspecialchars($message_data['content']));
//                    return Gateway::sendToCurrentClient(json_encode($new_message));
//                }

                //消息入库

//                $time = time();
//                $insert_id = self::$db->query("INSERT INTO `boiler_chat_room_msg` (`msg_room_id`,`msg_uid`,`msg_content`,`msg_addtime`) VALUES ($room_id,$uid,'{$content}',$time)");
                $uid = isset($message_data["from_uid"])?$message_data["from_uid"]:0;
                $from_headimg = isset($message_data["from_headimg"])?$message_data["from_headimg"]:'';
                $msg_id = isset($message_data["msg_id"])?$message_data["msg_id"]:0;
                $msg_type = isset($message_data["msg_type"])?$message_data["msg_type"]:1;
                $extra = isset($message_data["extra"])?$message_data["extra"]:'';
                $size = isset($message_data["size"])?$message_data["size"]:'';
                $content = nl2br(htmlspecialchars($message_data['content']));

                $new_message = array(
                    'type'=>'say',
                    'from_room_id'=>$room_id,
                    'from_client_id'=>$client_id,
                    'from_uid'=>$uid,
                    'from_headimg'=>$from_headimg,
                    'from_client_name' =>$client_name,
                    'to_client_id'=>'all',
                    'msg_id'=>$msg_id,
                    'msg_type'=>$msg_type,
                    'extra'=>$extra,
                    'size'=>$size,
                    'content'=>$content,
                    'time'=>date('Y-m-d H:i:s'),
                );



                self::sendGlobalForClient($room_id,$new_message);

                self::updateLastForUser($room_id,$msg_id);

                //self::updateLastForRoom($room_id,$msg_id,$uid,$client_name,$content);

                return Gateway::sendToGroup($room_id ,json_encode($new_message));
        }
   }

   /**
    * 当客户端断开连接时
    * @param integer $client_id 客户端id
    */
   public static function onClose($client_id)
   {
       // debug
       echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id onClose:''\n";
       
       // 从房间的客户端列表中删除
       if(isset($_SESSION['room_id']))
       {
           $room_id = $_SESSION['room_id'];
           $new_message = array('type'=>'logout', 'from_client_id'=>$client_id, 'from_client_name'=>$_SESSION['client_name'], 'time'=>date('Y-m-d H:i:s'));
           Gateway::sendToGroup($room_id, json_encode($new_message));
       }
   }


    /**
     * 当收到消息时，判断如果不是在聊天界面的，需要发送全局通知
     * @param $room_id
     */
   public static function sendGlobalForClient($room_id,$message)
   {

//获取全局链接的所有客户端
       $clientIds = Gateway::getAllClientSessions(-1);


//获取当前房间所有的客户端
       $currentUidArr = Gateway::getUidListByGroup($room_id);

       $uidKeyData = array();
       if(!empty($clientIds))
       {
           foreach ($clientIds as $key =>$itemData)
           {
               //给当前发消息用户不广播
               if(in_array($itemData["uid"],$currentUidArr)) continue;

               $uidKeyData[$itemData["uid"]] = $key;
           }


           if(empty($uidKeyData)) return;


           $uidStr = implode(",",array_keys($uidKeyData));

           $result = self::$db->select('config_uid')->from('boiler_chat_room_msg_config')->where('config_room_id='.$room_id.' and config_uid in ('.$uidStr.')')->query();


           if(!empty($result))
           {

               foreach ($result as $item)
               {
                   $currentClientId = $uidKeyData[$item["config_uid"]];
                   Gateway::sendToClient($currentClientId,json_encode($message));

               }

           }
       }
   }


    /**
     * 处在聊天框的人更新最后一条阅读消息
     * @param $room_id
     * @param $insert_id
     */
    public static function updateLastForUser($room_id,$insert_id)
    {

        $currentTime = time();
        $uidArr = Gateway::getUidListByGroup($room_id);
        if(!empty($uidArr))
        {

            foreach ($uidArr as $currentUid)
            {
                $result = self::$db->select('config_id')->from('boiler_chat_room_msg_config')->where('config_room_id='.$room_id.' and config_uid ='.$currentUid)->query();

                if(empty($result))
                {
                    self::$db->query("insert into boiler_chat_room_msg_config(config_room_id,config_uid,config_last_msg_id,config_lastupdate) values({$room_id},{$currentUid},{$insert_id},{$currentTime}) ");
                }
                else
                {
                    self::$db->query("update boiler_chat_room_msg_config set config_last_msg_id={$insert_id}, config_lastupdate={$currentTime} where config_room_id={$room_id} and config_uid ={$currentUid}");
                }
            }

        }
    }



    /**
     * 废弃
     * 处在聊天框的人更新最后一条阅读消息
     * @param $room_id
     * @param $insert_id
     */
    public static function updateLastForRoom($room_id,$msg_id,$uid,$uname,$content)
    {

        $currentTime = time();

        self::$db->query("update boiler_chat_room set room_last_msg_id={$msg_id},room_last_msg_uid={$uid},room_last_msg_uname='{$uname}',room_last_msg_content='{$content}',room_last_msg_addtime={$currentTime}
                          where room_id={$room_id}");


    }
}
