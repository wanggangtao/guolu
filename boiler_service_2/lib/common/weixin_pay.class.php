
<?php
/**
 * 微信支付服务器端下单
 * 微信APP支付文档地址:  https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=8_6
 * 使用示例
 *  构造方法参数
 *      'appid'     =>  //填写微信分配的公众账号ID
 *      'mch_id'    =>  //填写微信支付分配的商户号
 *      'notify_url'=>  //填写微信支付结果回调地址
 *      'key'       =>  //填写微信商户支付密钥
 *  );
 *  统一下单方法
 *  $WechatAppPay = new wechatAppPay($options);
 *  $params['body'] = '商品描述';                   //商品描述
 *  $params['out_trade_no'] = '1217752501201407';   //自定义的订单号，不能重复
 *  $params['total_fee'] = '100';                   //订单金额 只能为整数 单位为分
 *  $params['trade_type'] = 'APP';                  //交易类型 JSAPI | NATIVE |APP | WAP
 *  $wechatAppPay->unifiedOrder( $params );
 */
class weixin_pay
{
    //接口API URL前缀
    const API_URL_PREFIX = 'https://api.mch.weixin.qq.com';
    //下单地址URL
    const UNIFIEDORDER_URL = "/pay/unifiedorder";
    //查询订单URL
    const ORDERQUERY_URL = "/pay/orderquery";
    //关闭订单URL
    const CLOSEORDER_URL = "/pay/closeorder";

    const DOWNLOAD_URL = "/pay/downloadbill";
    //公众账号ID
    private $appid;
    //商户号
    private $mch_id;

    //支付密钥
    private $key;

    private $notify_url;

    //随机字符串
    private $nonce_str;
    //签名
    private $sign;
    //商品描述
    private $body;
    //商户订单号
    private $out_trade_no;
    //支付总金额
    private $total_fee;
    //终端IP
    private $spbill_create_ip;
    //支付结果回调通知地址
    //交易类型
    private $trade_type;

    //证书路径
    private $SSLCERT_PATH;
    private $SSLKEY_PATH;


    //所有参数
    private $params = array();
    public function __construct($appid, $mch_id, $notify_url="", $key="")
    {
        $this->appid = $appid;
        $this->mch_id = $mch_id;
        $this->notify_url = $notify_url;
        $this->key = $key;
    }


    /**
     * 下载对账单，WxPayDownloadBill中bill_date为必填参数
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayDownloadBill $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public  function downloadBill($params)
    {
        $url = self::API_URL_PREFIX.self::DOWNLOAD_URL;
        //检测必填参数
        if(!isset($params["bill_date"])||empty($params["bill_date"])) {
            throw new MyException("对账单接口中，缺少必填参数bill_date！",1001);
        }

        $this->nonce_str = $this->genRandomString();
        $this->params['appid'] = $this->appid;
        $this->params['mch_id'] = $this->mch_id;
        $this->params['nonce_str'] = $this->nonce_str;
        $this->params['bill_type'] = $params["bill_type"];

        $this->params['bill_date'] = $params["bill_date"];
        $this->sign = $this->MakeSign( $this->params );

        $this->params['sign'] = $this->sign;
        $xml = $this->data_to_xml($this->params);

        $count = 0;
        while($count < common_config::MAX_RETRY) {
            $response = self::postXmlCurl($xml, $url);
            if (substr($response, 0, 5) == "<xml>") {
                $count++;
                if($count>=common_config::MAX_RETRY){
                    break;
                }
                sleep(common_config::PERIOD);
                continue;
            }
            return $response;
        }
        $hostname = gethostname();
        sms::sendAwardMsg(alarm_config::alarm_mobile,"下载微信对账单失败[$hostname]");
        return "";
    }

    /**
     * @param $params
     * @param $LIB_COMMAON_PATH
     * @param $HTTP_PATH
     * @return bool|mixed
     */
    public function unifiedOrder( $params){
        $this->body = $params['body'];
        $this->out_trade_no = $params['out_trade_no'];
        $this->total_fee = $params['total_fee']*100;
        $this->trade_type = $params['trade_type'];
        $this->nonce_str = $this->genRandomString();
        $this->spbill_create_ip = $_SERVER['REMOTE_ADDR'];
        $this->params['appid'] = $this->appid;
        $this->params['mch_id'] = $this->mch_id;
        $this->params['nonce_str'] = $this->nonce_str;
        $this->params['body'] = $this->body;
        $this->params['out_trade_no'] = $this->out_trade_no;
        $this->params['total_fee'] = $this->total_fee;
        $this->params['spbill_create_ip'] = $this->spbill_create_ip;
        $this->params['notify_url'] = $this->notify_url;
        $this->params['trade_type'] = $this->trade_type;
        $this->params['attach'] = $params["attach"];

        if(isset($params["openid"]))
        {
            $this->params['openid'] = $params["openid"];

        }

        //获取签名数据
        $this->sign = $this->MakeSign( $this->params );
        $this->params['sign'] = $this->sign;
        $xml = $this->data_to_xml($this->params);
        $response = $this->postXmlCurl($xml, self::API_URL_PREFIX.self::UNIFIEDORDER_URL);
        if( !$response ){
            return false;
        }
        $result = $this->xml_to_data( $response );
        if( !empty($result['result_code']) && !empty($result['err_code']) ){
            $result['err_msg'] = $this->error_code( $result['err_code'] );
        }


        return $result;
    }
    /**
     * 查询订单信息
     * @param $out_trade_no     订单号
     * @return array
     */
    public function orderQuery( $out_trade_no ){
        $this->params['appid'] = $this->appid;
        $this->params['mch_id'] = $this->mch_id;
        $this->params['nonce_str'] = $this->genRandomString();
        $this->params['out_trade_no'] = $out_trade_no;
        //获取签名数据
        $this->sign = $this->MakeSign( $this->params );
        $this->params['sign'] = $this->sign;
        $xml = $this->data_to_xml($this->params);
        $response = $this->postXmlCurl($xml, self::API_URL_PREFIX.self::ORDERQUERY_URL);
        if( !$response ){
            return false;
        }
        $result = $this->xml_to_data( $response );
        if( !empty($result['result_code']) && !empty($result['err_code']) ){
            $result['err_msg'] = $this->error_code( $result['err_code'] );
        }
        return $result;
    }
    /**
     * 关闭订单
     * @param $out_trade_no     订单号
     * @return array
     */
    public function closeOrder( $out_trade_no ){
        $this->params['appid'] = $this->appid;
        $this->params['mch_id'] = $this->mch_id;
        $this->params['nonce_str'] = $this->genRandomString();
        $this->params['out_trade_no'] = $out_trade_no;
        //获取签名数据
        $this->sign = $this->MakeSign( $this->params );
        $this->params['sign'] = $this->sign;
        $xml = $this->data_to_xml($this->params);
        $response = $this->postXmlCurl($xml, self::API_URL_PREFIX.self::CLOSEORDER_URL);
        if( !$response ){
            return false;
        }
        $result = $this->xml_to_data( $response );
        return $result;
    }
    /**
     *
     * 获取支付结果通知数据
     * return array
     */
    static public function getNotifyData(){
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
     * 接收通知成功后应答输出XML数据
     * @param string $xml
     */
    static public function replyNotify($code,$msg){
        $data['return_code'] = $code;
        $data['return_msg'] = $msg;
        $xml = self::data_to_xml( $data );
        echo $xml;
    }
    /**
     * 生成APP端支付参数
     * @param  $prepayid   预支付id
     */
    public function getAppPayParams( $prepayid ){
        $data['appid'] = $this->appid;
        $data['partnerid'] = $this->mch_id;
        $data['prepayid'] = $prepayid;
        $data['package'] = 'Sign=WXPay';
        $data['noncestr'] = $this->genRandomString();
        $data['timestamp'] = time();
        $data['sign'] = $this->MakeSign( $data );

        return $data;
    }


    /**
     * 生成APP端支付参数
     * @param  $prepayid   预支付id
     */
    public function getJsPayParams( $prepayid ){
        $data['appId'] = $this->appid;
        $data['timeStamp'] = (String)time();

        $data['package'] = 'prepay_id='.$prepayid;
        $data['nonceStr'] = $this->genRandomString();
        $data['signType'] = "MD5";

        $data['paySign'] = $this->MakeSign( $data );

        return $data;
    }


    /**
     * 生成签名
     *  @return 签名
     */
    public function MakeSign( $params ){
        //签名步骤一：按字典序排序数组参数
        ksort($params);
        $string = $this->ToUrlParams($params);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$this->key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }
    /**
     * 将参数拼接为url: key=value&key=value
     * @param   $params
     * @return  string
     */
    public function ToUrlParams( $params ){
        $string = '';
        if( !empty($params) ){
            $array = array();
            foreach( $params as $key => $value ){
                if($key != "sign" && $value != "" && !is_array($value)) {
                    $array[] = $key . '=' . $value;
                }
            }
            $string = implode("&",$array);
        }
        return $string;
    }
    /**
     * 输出xml字符
     * @param   $params     参数名称
     * return   string      返回组装的xml
     **/
    public function data_to_xml( $params ){
        if(!is_array($params)|| count($params) <= 0)
        {
            return false;
        }
        $xml = "<xml>";
        foreach ($params as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
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
     * 获取毫秒级别的时间戳
     */
    private static function getMillisecond(){
        //获取毫秒的时间戳
        $time = explode ( " ", microtime () );
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode( ".", $time );
        $time = $time2[0];
        return $time;
    }
    /**
     * 产生一个指定长度的随机字符串,并返回给用户
     * @param type $len 产生字符串的长度
     * @return string 随机字符串
     */
    private function genRandomString($len = 32) {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        // 将数组打乱
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }
    /**
     * 以post方式提交xml到对应的接口url
     *
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    private function postXmlCurl($xml, $url, $useCert = false, $second = 30){
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            //curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            //curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return false;
        }
    }
    /**
     * 错误代码
     * @param  $code       服务器输出的错误代码
     * return string
     */
    public function error_code( $code ){
        $errList = array(
            'NOAUTH'                =>  '商户未开通此接口权限',
            'NOTENOUGH'             =>  '用户帐号余额不足',
            'ORDERNOTEXIST'         =>  '订单号不存在',
            'ORDERPAID'             =>  '商户订单已支付，无需重复操作',
            'ORDERCLOSED'           =>  '当前订单已关闭，无法支付',
            'SYSTEMERROR'           =>  '系统错误!系统超时',
            'APPID_NOT_EXIST'       =>  '参数中缺少APPID',
            'MCHID_NOT_EXIST'       =>  '参数中缺少MCHID',
            'APPID_MCHID_NOT_MATCH' =>  'appid和mch_id不匹配',
            'LACK_PARAMS'           =>  '缺少必要的请求参数',
            'OUT_TRADE_NO_USED'     =>  '同一笔交易不能多次提交',
            'SIGNERROR'             =>  '参数签名结果不正确',
            'XML_FORMAT_ERROR'      =>  'XML格式错误',
            'REQUIRE_POST_METHOD'   =>  '未使用post传递参数 ',
            'POST_DATA_EMPTY'       =>  'post数据不能为空',
            'NOT_UTF8'              =>  '未使用指定编码格式',
        );
        if( array_key_exists( $code , $errList ) ){
            return $errList[$code];
        }
    }



}