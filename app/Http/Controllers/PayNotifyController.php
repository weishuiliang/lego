<?php


namespace App\Http\Controllers;


use App\Helpers\Helper;
use App\Models\Log;
use Illuminate\Http\Request;

class PayNotifyController
{

    /**
     * notify
     *
     * @author wsl
     * @param Request $request
     * @return string
     * @throws \Exception
     * @see https://pay.weixin.qq.com/wiki/doc/api/wxa/wxa_api.php?chapter=9_7&index=8
     */
    public function notify(Request $request)
    {
        $all = $request->all();

        Log::saveLog('weixin payNotify xml', json_encode($all));

        $data = Helper::FromXml($all);
        Log::saveLog('weixin payNotify array', json_encode($data));

        // todo 支付成功
        // todo xml 解析
        // 要做签名验证

        $returnData = [
            'return_code' => 'SUCCESS',
            'return_msg' => 'OK'
        ];

        return Helper::ToXml($returnData);
    }


}