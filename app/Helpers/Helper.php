<?php


namespace App\Helpers;


class Helper
{


    public static function responseJson($data = [], $code = 1, $msg = "success")
    {
        return response()->json(['code' => $code, 'msg' => $msg, 'data' => $data])
            ->setEncodingOptions( JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * 生成订单编号
     *
     * @param $userId
     * @param $lessonId
     * @return string
     * @author wsl
     */
    public static function generateOrderNo($userId, $lessonId)
    {
        return date('YmdHis') . str_pad($userId, 6, 0, STR_PAD_LEFT) . str_pad($lessonId, 6, 0, STR_PAD_LEFT);
    }



    /**
     * ToXml
     *
     * @author wsl
     * @param $data
     * @return string
     * @throws \Exception
     */
    public static function ToXml($data)
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
    public static function FromXml($xml)
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
}