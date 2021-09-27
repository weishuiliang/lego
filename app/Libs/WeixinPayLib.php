<?php


namespace App\Libs;

use App\Constants\ErrorCode;
use App\Helpers\Helper;
use App\Models\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class WeixinPayLib
 * @author wsl
 * @package App\Libs
 * @see https://pay.weixin.qq.com/wiki/doc/api/wxa/wxa_api.php?chapter=9_1&index=1
 */
class WeixinPayLib
{

    private $unified_order_url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

    private $order_query_url = 'https://api.mch.weixin.qq.com/pay/orderquery';

    //小程序Id
    private $appid;

    //商户Id
    private $mch_id;

    private $mch_key;

    private $device_info = 'micro';

    private $nonce_str;

    private $sign;

    private $sign_type = 'MD5';

    private $body = '乐高小程序-课程';
    //订单号
    private $out_trade_no;
    //币种，默认CNY
    private $fee_type = 'CNY';
    //支付金额
    private $total_fee;
    // ip地址
    private $spbill_create_ip;
    //回调地址
    private $notify_url;

    private $trade_type = 'JSAPI';

    private $openid;


    public function __construct()
    {
        $this->appid = env('MICRO_APPID');
        $this->mch_id = env('MCH_ID');
        $this->mch_key = env('MCH_KEY');
        $this->notify_url = env('SERVER_URL') . '/weixin/pay/notify';
    }

    /**
     * unifiedOrder 统一下单
     * 这边只是获取一个预支付订单号
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @author wsl
     */
    public function unifiedOrder($params)
    {
        $data = [
            'appid' => $this->appid,
            'mch_id' => $this->mch_id,
            'nonce_str' => $this->getNonceStr(),
            'body' => $this->body,
            'out_trade_no' => $params['order_no'],
            'total_fee' => $params['amount'],
            'spbill_create_ip' => $params['ip'],
            'openid' => $params['openid'],
            'notify_url' => $this->notify_url,
            'trade_type' => $this->trade_type,
        ];
        $data['sign'] = $this->generateSign($data);

        Log::saveLog('create order params', json_encode($data));

        try {
            $xml = $this->ToXml($data);
            $resultXml = $this->postXmlCurl($this->unified_order_url, $xml);
            $result = $this->FromXml($resultXml);

            Log::saveLog('create order result', json_encode($result));

            if ('SUCCESS' != $result['return_code']) {
                throw new \Exception("网络异常");
            }
            if ('SUCCESS' != $result['result_code']) {
                throw new \Exception($result['err_code_des']);
            }

            return $result;
        } catch (ClientException $e) {
            throw new \Exception("网络异常");
        } catch (\Exception $e) {
            throw new \Exception("支付失败");
        }
    }

    /**
     * 查询订单信息
     *
     * @author wsl
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function queryOrderInfo($params)
    {
        $data = [
            'appid' => $this->appid,
            'mch_id' => $this->mch_id,
            'out_trade_no' => $params['order_no'],
            'nonce_str' => $this->getNonceStr(),
        ];
        $data['sign'] = $this->generateSign($data);

        Log::saveLog('query order params', json_encode($data));

        try {
            $xml = $this->ToXml($data);
            $resultXml = $this->postXmlCurl($this->order_query_url, $xml);
            $result = $this->FromXml($resultXml);

            Log::saveLog('query order result', json_encode($result));

            if ('SUCCESS' != $result['return_code']) {
                throw new \Exception("网络异常");
            }
            if ('SUCCESS' != $result['result_code']) {
                throw new \Exception($result['err_code_des']);
            }

            return $result;
        } catch (ClientException $e) {
            throw new \Exception("网络异常");
        } catch (\Exception $e) {
            throw new \Exception("查询失败");
        }
    }


    /**
     * 生成签名
     * @param array $data
     * @return string
     */
    public function generateSign($data)
    {
        //签名步骤一：按字典序排序参数
        ksort($data);
        $string = $this->toUrlParams($data);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $this->mch_key;
        //签名步骤三：MD5加密或者HMAC-SHA256
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }





    /**
     * 获取随机数
     *
     * @param int $length
     * @return string
     * @author wsl
     */
    private static function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 前后拼接
     *
     * @param $data
     * @return string
     * @author wsl
     */
    public function toUrlParams($data)
    {
        $buff = "";
        foreach ($data as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * ToXml
     *
     * @author wsl
     * @param $data
     * @return string
     * @throws \Exception
     */
    public function ToXml($data)
    {
        if (!is_array($data) || count($data) <= 0) {
            throw new \Exception("数组数据异常！");
        }

        $xml = "<xml>";
        foreach ($data as $key => $val)
        {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     *
     * @author wsl
     * @param $xml
     * @return mixed
     * @throws \Exception
     */
    public function FromXml($xml)
    {
        if(!$xml){
            throw new \Exception("xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $result = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $result;
    }

    /**
     * postXmlCurl
     *
     * @author wsl
     * @param $config
     * @param $xml
     * @param $url
     * @param bool $useCert
     * @param int $second
     * @return bool|string
     * @throws \Exception
     */
    private static function postXmlCurl($url, $xml, $second = 30)
    {
        $ch = curl_init();
        $curlVersion = curl_version();
//        $ua = "WXPaySDK/".self::$VERSION." (".PHP_OS.") PHP/".PHP_VERSION." CURL/".$curlVersion['version']." "
//            .$config->GetMerchantId();

        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        $proxyHost = "0.0.0.0";
        $proxyPort = 0;
//        $config->GetProxy($proxyHost, $proxyPort);
        //如果有配置代理这里就设置代理
        if($proxyHost != "0.0.0.0" && $proxyPort != 0){
            curl_setopt($ch,CURLOPT_PROXY, $proxyHost);
            curl_setopt($ch,CURLOPT_PROXYPORT, $proxyPort);
        }
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
//        curl_setopt($ch,CURLOPT_USERAGENT, $ua);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

//        if($useCert == true){
//            //设置证书
//            //使用证书：cert 与 key 分别属于两个.pem文件
//            //证书文件请放入服务器的非web目录下
//            $sslCertPath = "";
//            $sslKeyPath = "";
////            $config->GetSSLCertPath($sslCertPath, $sslKeyPath);
//            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
//            curl_setopt($ch,CURLOPT_SSLCERT, $sslCertPath);
//            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
//            curl_setopt($ch,CURLOPT_SSLKEY, $sslKeyPath);
//        }
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
            throw new \Exception("curl出错，错误码:$error");
        }
    }
}