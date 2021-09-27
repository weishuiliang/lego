<?php


namespace App\Http\Controllers\Micro;

use App\Helpers\Helper;
use App\Models\Teacher;
use Illuminate\Http\Request;


class TeacherController
{
    /**
     * 教师列表
     *
     * @author wsl
     * @return Helper
     */
    public function list(Request $request)
    {
        $teacherName = $request->input('teacher_name', '');
        $honoraryTitle = $request->input('honorary_title', '');

        $query = Teacher::query()->where('is_show', 1);
        if (!empty($teacherName)) {
            $query->where('teacher_name', 'like', '%' . $teacherName . '%');
        }
        if (!empty($honoraryTitle)) {
            $query->where('honorary_title', 'like', '%' . $honoraryTitle . '%');
        }

        $list = $query->get();
        if ($list) {
            $list = $list->toArray();
        }
        return Helper::responseJson($list);
    }

    /**
     * 教师详情
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function detail(Request $request)
    {
        $id = $request->input('id', '');
        if (empty($id)) {
            return Helper::responseJson([], 0, '缺少ID');
        }

        $detail = Teacher::query()->find($id);
        return Helper::responseJson($detail);
    }

}