<?php


namespace App\Http\Controllers\Admin;


use App\Helpers\Helper;
use App\Models\Lesson;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class UserController
{


    public function detail(Request $request)
    {
        $id = $request->input('id', '');
        if (empty($id)) {
            return Helper::responseJson([], 0, '缺少ID');
        }

        $detail = User::query()->find($id);
        return Helper::responseJson($detail);
    }

    public function list(Request $request)
    {
        $userName = $request->input('user_name', '');
        $mobile = $request->input('mobile', '');

        $query = User::query();
        if (!empty($userName)) {
            $query->where('user_name', 'like', '%' . $userName . '%');
        }
        if (!empty($mobile)) {
            $query->where('mobile', 'like', '%' . $mobile . '%');
        }
        $list = $query->get();
        if ($list) {
            $list = $list->toArray();
        }
        return Helper::responseJson($list);
    }

}