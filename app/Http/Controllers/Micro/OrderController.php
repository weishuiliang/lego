<?php


namespace App\Http\Controllers\Micro;


use App\Helpers\Helper;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController
{

    public function detail(Request $request)
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

        return Helper::responseJson($order);
    }

}