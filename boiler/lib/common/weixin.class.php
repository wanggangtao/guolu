<?php
/**
 * Created by PhpStorm.
 * User: xiepeng
 * Date: 2017/8/17
 */
class weixin
{

    /**线上***/


    const SCOPE_USERINFO = 'snsapi_base'; //授权方法

    /**测试***/


    const TOKEN_BASE_KEY = "weixin_access_token";
    const JS_TICKET_KEY = "weixin_js_ticket";
    const LimitTime = 7100;



    public $setFlag = false;
    public $msgtype = 'text';   //('text','image','location')
    public $msg = array();

    /****微信支付支付向相关参数****/


    public function __construct()
    {
    }

    public function index()
    {

        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $token = 'weixin_xian_rd_2018';
        $signature = $_GET['signature'];
        $echostr = $_GET['echostr'];

        $array = array($timestamp, $nonce, $token);
        sort($array);
        $tmpstr = sha1(implode('', $array));
        if ($tmpstr == $signature && $echostr) {
            echo $echostr;
        }
    }

    public function getAccessToken()
    {
        $rs = Baseconfig::getInfoByKey(self::TOKEN_BASE_KEY);

        if (empty($rs) || (time() - $rs["lastupdate"]) > self::LimitTime) {
            $accessToken = self::refreshToken();
        } else {
            $accessToken = $rs["value"];
        }
        return $accessToken;
    }

    public function refreshToken()
    {

        $conf = $this->_conf();
        $token_access_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $conf["appid"] . "&secret=" . $conf["appkey"];

        $res = file_get_contents($token_access_url); //
        $result = json_decode($res, true); //接受一个 JSON 格式的字符串并且把它转换为 PHP 变量s
        $access_token = $result['access_token'];

        $rs = Baseconfig::getInfoByKey(self::TOKEN_BASE_KEY);
        if ($rs) {
            $res = Baseconfig::update($rs["id"], array("value" => $access_token, "lastupdate" => time()));
        } else {
            $res = Baseconfig::add(self::TOKEN_BASE_KEY, $access_token, "微信开发access_token");
        }
        if ($res) {
            return $access_token;
        }
    }

    public function getUserInfo($openId)
    {
        $token = $this->getAccessToken();
        $token_access_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $token . "&openid=" . $openId . "&lang=zh_CN";
        $res = file_get_contents($token_access_url); //
        return json_decode($res, true); //接受一个 JSON 格式的字符串并且把它转换为 PHP 变量
    }

    public function getInfoByCode()
    {
        $code = isset($_REQUEST["code"])?$_REQUEST["code"]:"";
        if(empty($code))
        {
            return 0;
        }

        $conf = $this->_conf();

        $token_access_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $conf["appid"] . "&secret=" . $conf["appkey"] . "&code=" . $code . "&grant_type=authorization_code";
        $res = file_get_contents($token_access_url); //
        return json_decode($res, true); //接受一个 JSON 格式的字符串并且把它转换为 PHP 变量，若不加TURE,则是一个对象
    }

    public function createMenu()
    {

        global $HTTP_PATH;
        $access_token = $this->getAccessToken();
        $data = '{
                     "button":[

                     {
                          "name":"使用贴士",

                          "sub_button":[{

                          "type":"view",
                          "name":"锅炉常识",
                          "url":"'.$HTTP_PATH.'weixin/weixin_industry_info.php"

                          },
                          {
                          "type":"view",
                          "name":"使用指南",
                          "url":"'.$HTTP_PATH.'weixin/weixin_product_describe.php"
                          }
                          ]
                     },
                     {
                         "name":"我的",

                          "sub_button":[
                          {
                           "type":"view",
                           "name":"我的优惠券",
                           "url":"'.$HTTP_PATH.'weixin/weixin_coupon_have.php"               
                          },
                          {
                           "type":"view",
                           "name":"个人信息",
                           "url":"'.$HTTP_PATH.'weixin/weixin_personal_detail.php"               
                          },
                          {
                           "type":"view",
                           "name":"师傅端入口",
                           "url":"'.$HTTP_PATH.'weixin/master_index.php"               
                          },
                          ]

                         

                     }

                     ]
                }';

//        $data = '{
//                     "button":[
//
//
//                     {
//
//
//                          "type":"view",
//                          "name":"使用贴士",
//                          "url":"'.$HTTP_PATH.'weixin/weixin_industry_info.php"
//
//                     },
//                     {
//
//                          "type":"view",
//                          "name":"个人信息",
//                          "url":"'.$HTTP_PATH.'weixin/weixin_personal_detail.php"
//
//                     }
//
//                     ]
//                }';

        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $access_token;

        return $this->curlPost($data, $url);
    }

    public function curlPost($data, $url)
    {
        //var_dump($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        //   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);

        if (curl_errno($ch)) {
            return curl_error($ch) ;
        }

        curl_close($ch);

        return $tmpInfo;
    }



    public function getMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if (!empty($postStr)) {
            $this->msg = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->msgtype = strtolower($this->msg['MsgType']);
        }
    }

    public function makeText($text='')
    {
        $CreateTime = time();
        $FuncFlag = $this->setFlag ? 1 : 0;
        $textTpl = "<xml>
            <ToUserName><![CDATA[{$this->msg['FromUserName']}]]></ToUserName>
            <FromUserName><![CDATA[{$this->msg['ToUserName']}]]></FromUserName>
            <CreateTime>{$CreateTime}</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>%s</FuncFlag>
            </xml>";
        return sprintf($textTpl,$text,$FuncFlag);
    }



    public function makeTextPic($arr)
    {
        $CreateTime = time();

        $len = count($arr);
        $textTpl = "<xml><ToUserName><![CDATA[{$this->msg['FromUserName']}]]></ToUserName><FromUserName><![CDATA[{$this->msg['ToUserName']}]]></FromUserName><CreateTime>{$CreateTime}</CreateTime><MsgType><![CDATA[news]]></MsgType><ArticleCount>{$len}</ArticleCount><Articles>";

        $itemTpl = "<item><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item>";
        foreach ($arr as $item)
        {
            $textTpl .= sprintf($itemTpl,$item["title"],$item["description"],$item["picUrl"],$item["url"]);
        }

        $textTpl .= "</Articles></xml>";
        return $textTpl;

    }

    public function reply($data)
    {
        echo $data;
    }



    public function getSignPackage() {
        $jsapiTicket = $this->getJsTicket();

        $conf = $this->_conf();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $nonceStr = $this->createNonceStr();
        $timestamp = time();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $conf["appid"],
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );

        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }


    public function downLoadFile($serverId,$foldername){

        $access_token = $this->getAccessToken();


        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$serverId;
        if (!file_exists($foldername)) {
            mkdir($foldername, 0777, true);
        }
        $targetName = date('YmdHis').rand(1000,9999).'.jpg';

        $targetPathName = $foldername.$targetName;

        //获取微信“获取临时素材”接口返回来的内容（即刚上传的图片）
        $a = file_get_contents($url);
        //以读写方式打开一个文件，若没有，则自动创建
        $resource = fopen($targetPathName , 'w+');
        //将图片内容写入上述新建的文件
        fwrite($resource, $a);
        //关闭资源
        fclose($resource);

        return $targetName;
    }

    public function sendImageToUser($openId,$mediaId)
    {
        $acccess_token = $this->getAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$acccess_token}";
        $sendData=' {
                        "touser":"'.$openId.'",
                        "msgtype":"image",
                        "image":
                        {
                          "media_id":"'.$mediaId.'"
                        }
                    }';

        return $this->curlPost($sendData,$url);
    }


    public function sendMsgToUser($openId,$content)
    {
        $acccess_token = $this->getAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$acccess_token}";
        $sendData='{
                        "touser": "'.$openId.'",
                        "msgtype": "text",
                        "text": {
                                "content": "'.$content.'"
                        }
                    }';

        return $this->curlPost($sendData,$url);

    }


    public function getJsTicket(){ // 只允许本类调用，继承的都不可以调用，公开调用就更不可以了

        $rs = Baseconfig::getInfoByKey(self::JS_TICKET_KEY);

        if(empty($rs)||(time()-$rs["lastupdate"])>self::LimitTime)
        {
            $jsticket = self::refreshJsTicket();
        }
        else
        {
            $jsticket = $rs["value"];
        }

        return $jsticket ;

    }

    public function refreshJsTicket()
    {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$access_token."&type=jsapi"; // 两小时有效
        $rurl = file_get_contents($url);
        $rurl = json_decode($rurl,true);
        if($rurl['errcode'] != 0){
            return false;
        }else{
            $jsticket = $rurl['ticket'];
            $rs = Baseconfig::getInfoByKey(self::JS_TICKET_KEY);
            if($rs)
            {
                $res = Baseconfig::update($rs["id"],array("value"=>$jsticket,"lastupdate"=>time()));
            }
            else
            {
                $res = Baseconfig::add(self::JS_TICKET_KEY,$jsticket,"微信开发js_ticket");
            }

            if($res)
            {
                return $jsticket;
            }
        }
    }


    /**
     *
     * 获取支付结果通知数据
     * return array
     */
    public function getNotifyData(){
        //获取通知的数据
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $data = array();
        if( empty($xml) ){
            return false;
        }
        $data = self::xml_to_data( $xml );
        if( !empty($data['return_code']) ){
            if( $data['return_code'] == 'FAIL' ){
                return false;
            }
        }
        return $data;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * return array
     */
    public function xml_to_data($xml){
        if(!$xml){
            return false;
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }


    /**
     * 用户隐秘授权
     * @param $redirectUrl
     * @return string
     */
    function login_url($redirectUrl)
    {

        $conf = $this->_conf();

        $params = array(
            'appid' => $conf["appid"],
            'redirect_uri' => $redirectUrl,
            'response_type' => 'code',
            'scope' => self::SCOPE_USERINFO,
            'state' => time(),
        );


        return 'https://open.weixin.qq.com/connect/oauth2/authorize?' . http_build_query($params) . '#wechat_redirect';
    }


    public static function savePicToServer($url,$fileName) {
        // 要存在你服务器哪个位置？
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $fp = fopen($fileName,'wb');
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_FILE,$fp);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

    }



    public function _conf()
    {

        $arr = Baseconfig::getInfoByArr(array(Baseconfig::CFG_WEIXIN_APPID,Baseconfig::CFG_WEIXIN_APPKEY));
        $info = array();
        foreach ($arr as $v)
        {
            if ($v['key'] == Baseconfig::CFG_WEIXIN_APPID)
            {
                $info['appid'] = $v['value'];
            }
            if ($v['key'] == Baseconfig::CFG_WEIXIN_APPKEY)
            {
                $info['appkey'] = $v['value'];
            }
        }

        return $info;
    }
    public function sendMsgToUserHaveUrl($openid,$text)
    {

        $ToUserName = Baseconfig::getInfoByKey(Baseconfig::CFG_WEIXIN_APPID)['value'];
        $CreateTime = time();

        $textTpl = "<xml>
            <ToUserName><![CDATA[$ToUserName}]]></ToUserName>
            <FromUserName><![CDATA[{$openid}]]></FromUserName>
            <CreateTime>{$CreateTime}</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>";

        return sprintf($textTpl,$text);
    }


    public   function get_template(){
        $acccess_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=".$acccess_token;
        $sendData='';
        $res =  $this->curlPost($sendData,$url);

        return json_decode($res, true);


    }
//推送用户下单时的订单，客服推送
    public function send_user_by_template($dataUrl,$openid,$dataInfo,$template_id) { //发送$kf_template_id模板消息
        $acccess_token = $this->getAccessToken();

        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$acccess_token}";

        $data =' {
           "touser":"'.$openid.'",
           "template_id":"'.$template_id.'",
           "url":"'.$dataUrl.'",        
           "data":{
                   "first": {
                       "value":"您收到一个新的预约订单，请及时处理",
                       "color":"#173177"
                   },
                   "keyword1":{
                         "value": "'.$dataInfo['type'].'"
                   },
                   "keyword2": {
                        "value":"'.$dataInfo['register_person'].'"
                   },
                   "keyword3": {
                        "value":"'.$dataInfo['link_phone'].'"
                   },
                    "keyword4": {
                        "value":"'.$dataInfo['register_phone'].'"
                   },
                    "keyword5": {
                        "value":"'.$dataInfo['address'].'"
                   },  
                   "remark":{
                        "value":"点击查看订单详情",
                        "color":"#173177"
                   }
           }
       }';
        $result = $this->curlPost($data , $url);

//        $result = $this->send_post( $url, $data);
        return $result;
    }
  //发放的优惠劵推送
    public function send_coupon_by_template($dataUrl,$openid,$dataInfo,$template_id) { //发送模板消息
        $acccess_token = $this->getAccessToken();

        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$acccess_token}";


        $data =' {
           "touser":"'.$openid.'",
           "template_id":"'.$template_id.'",
           "url":"'.$dataUrl.'",        
           "data":{
                   "first": {
                       "value":"您收到了由“小元服务”为您发放的优惠券",
                       "color":"#173177"
                   },
                   "keyword1":{
                         "value": "'.$dataInfo['type'].'"
                   },
                   "keyword2": {
                        "value":"'.$dataInfo['time'].'"
                   },
                   
                   "remark":{
                        "value":"点击查看优惠券",
                        "color":"#173177"
                   }
           }
       }';

        $result = $this->curlPost($data , $url);
        return $result;
    }

    //推送派单消息的模板函数
    //user sxx
    public function send_coupon_by_templates($dataUrl,$openid,$dataInfo,$template_id) {
        $acccess_token = $this->getAccessToken();

        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$acccess_token}";

        $data =' {
           "touser":"'.$openid.'",
           "template_id":"'.$template_id.'",
           "url":"'.$dataUrl.'",        
           "data":{
                   "first": {
                       "value":"您已成功预约",
                       "color":"#173177"
                   },
                   "keyword1":{
                         "value": "'.$dataInfo['type'].'"
                   },
                   "keyword2": {
                        "value":"'.$dataInfo['time'].'"
                   },
                   
                   "remark":{
                        "value":"点击查看详情",
                        "color":"#173177"
                   }
           }
       }';

        $result = $this->curlPost($data , $url);
        return $result;
    }

    //推送已接单消息的模板函数
    //user sxx
    public function send_coupon_by_templatess($dataUrl,$openid,$dataInfo,$template_id) {
        $acccess_token = $this->getAccessToken();

        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$acccess_token}";

        $data =' {
           "touser":"'.$openid.'",
           "template_id":"'.$template_id.'",
           "url":"'.$dataUrl.'",        
           "data":{
                   "first": {
                       "value":"维修师傅已接单，请保持电话畅通",
                       "color":"#173177"
                   },
                   "keyword1":{
                         "value": "'.$dataInfo['name'].'"
                   },
                   "keyword2": {
                        "value":"'.$dataInfo['linkphone'].'"
                   },
                   "keyword3": {
                        "value":"'.$dataInfo['type'].'"
                   },
                   "keyword4": {
                        "value":"'.$dataInfo['time'].'"
                   },
                   
                   "remark":{
                        "value":"点击查看详情",
                        "color":"#173177"
                   }
           }
       }';

        $result = $this->curlPost($data , $url);
        return $result;
    }

    //推送待支付消息的模板函数
    //user sxx
    public function send_coupon_by_pay($dataUrl,$openid,$dataInfo,$template_id) {
        $acccess_token = $this->getAccessToken();

        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$acccess_token}";

        $data =' {
           "touser":"'.$openid.'",
           "template_id":"'.$template_id.'",
           "url":"'.$dataUrl.'",        
           "data":{
                   "first": {
                       "value":"您预约的订单已经维修完成，请您尽快支付",
                       "color":"#173177"
                   },
                   "keyword1":{
                         "value": "'.$dataInfo['time'].'"
                   },
                   "keyword2": {
                        "value":"'.$dataInfo['name'].'"
                   },
                   "keyword3": {
                        "value":"'.$dataInfo['money1'].'"
                   },
                   "keyword4": {
                        "value":"'.$dataInfo['money2'].'"
                   },
                   "keyword5": {
                        "value":"'.$dataInfo['money3'].'"
                   },
                   
                   "remark":{
                        "value":"点击查看详情",
                        "color":"#173177"
                   }
           }
       }';

        $result = $this->curlPost($data , $url);
        return $result;
    }

    //推送支付成功消息的模板函数
    //user sxx
    public function send_coupon_by_finsh($dataUrl,$openid,$dataInfo,$template_id) {
        $acccess_token = $this->getAccessToken();

        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$acccess_token}";

        $data =' {
           "touser":"'.$openid.'",
           "template_id":"'.$template_id.'",
           "url":"'.$dataUrl.'",        
           "data":{
                   "first": {
                       "value":"您已成功支付'.$dataInfo['num'].'",
                       "color":"#173177"
                   },
                   "keyword1":{
                         "value": "'.$dataInfo['type'].'"
                   },
                   "keyword2": {
                        "value":"'.$dataInfo['time'].'"
                   },
                   
                   "remark":{
                        "value":"点击查看详情",
                        "color":"#173177"
                   }
           }
       }';

        $result = $this->curlPost($data , $url);
        return $result;
    }



    public function send_post( $url, $post_data )
    {
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/json;charset=utf-8',
                //header 需要设置为 JSON
                'content' => $post_data,
                'timeout' => 60
                //超时时间
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }





}