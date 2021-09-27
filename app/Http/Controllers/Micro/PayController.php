<?php


namespace App\Http\Controllers\Micro;


use App\Constants\OrderConstant;
use App\Helpers\Helper;
use App\Libs\WeixinPayLib;
use App\Models\Lesson;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class PayController
{

    /**
     * 创建在本系统中的订单
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createOrder(Request $request)
    {
        $accessToken = $request->input('access_token', '');
        $lessonId = $request->input('lesson_id', 0);

        if (empty($accessToken)) {
            return Helper::responseJson([], 0, '缺少accessToken');
        }
        if (empty($lessonId)) {
            return Helper::responseJson([], 0, '请选择订购的课程');
        }
        $user = User::query()->where('access_token', $accessToken)->first();
        if (empty($user)) {
            return Helper::responseJson([], 0, '该用户不存在');
        }
        $userId = $user->user_id;
        $currentTime = date('Y-m-d H:i:s', time());

        $lesson = Lesson::query()->find($lessonId);
        if (empty($lesson)) {
            return Helper::responseJson([], 0, '该课程不存在或已结束');
        }
        //todo 如果重新发起支付的处理
        $order = new Order();
        $order->user_id = $userId;
        $order->lesson_id = $lessonId;
        $order->order_no = Helper::generateOrderNo($userId, $lessonId);
        $order->amount = $lesson->lesson_amount;
        $order->ip = $request->getClientIp();
        $saveResult = $order->save();
        if (false == $saveResult) {
            return Helper::responseJson([], 0, '创建订单失败');
        }
        try {
            $data = $order->toArray();
            $data['openid'] = $user->openid;
            $payLib = new WeixinPayLib();
            $result = $payLib->unifiedOrder($data);

            $order->prepay_id = $result['prepay_id'];
            $order->save();
            return Helper::responseJson($order);
        } catch (\Exception $e) {
            return Helper::responseJson([], 0, '微信支付失败' . $e->getMessage());
        }
    }

    /**
     * 查询订单信息
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     * @throws \Exception
     */
    public function queryOrderPayStatus(Request $request)
    {
        $orderNo = $request->input('order_no', '');
        if (empty($orderNo)) {
            return Helper::responseJson([], 0, '缺少accessToken');
        }
        $order = Order::query()
            ->where('order_no', $orderNo)
            ->first();
        if (empty($order)) {
            return Helper::responseJson([], 0, '未找到该订单');
        }
        // 24h内的订单进行查询 todo
        if (OrderConstant::PAY_STATUS_NO === $order->pay_status) {
            $payLib = new WeixinPayLib();
            $data['order_no'] = $orderNo;
            $result = $payLib->queryOrderInfo($data);

            if (isset($result['trade_state']) && 'SUCCESS' === $result['trade_state']) {
                $order->pay_status = OrderConstant::PAY_STATUS_YES;
                $order->pay_time = time();
                $order->save();
            }

            return Helper::responseJson($result, 1);
        }

        $result['trade_state'] = 'SUCCESS';
        return Helper::responseJson($result, 1, '支付成功');
    }





}