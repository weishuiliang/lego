<?php


namespace App\Http\Controllers\Micro;


use App\Helpers\Helper;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController
{


    /**
     * 可预约的课程
     *
     * @author wsl
     * @return Helper
     */
    public function appointmentLessonlist()
    {
        $currentTime = date('Y-m-d H:i:s');
        $list = Lesson::query()->where('start_time', '>', $currentTime)->get();
        if ($list) {
            $list = $list->toArray();
        }
        return Helper::responseJson($list);
    }

    /**
     * 课程详情
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

        $detail = Lesson::query()->find($id);
        return Helper::responseJson($detail);
    }

    public function list(Request $request)
    {
        $lessonName = $request->input('lesson_name', '');
        $applicableAge = $request->input('applicable_age', '');

        $query = Lesson::query()->where('is_show', 1);
        if (!empty($lessonName)) {
            $query->where('lesson_name', 'like', '%' . $lessonName . '%');
        }
        if (!empty($applicableAge)) {
            $query->where('applicable_age', $applicableAge);
        }

        $list = $query->get();
        if ($list) {
            $list = $list->toArray();
        }
        return Helper::responseJson($list);
    }
}