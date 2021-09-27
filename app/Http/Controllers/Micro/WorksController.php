<?php


namespace App\Http\Controllers\Micro;

use App\Helpers\Helper;
use App\Models\Lesson;
use App\Models\Works;
use Illuminate\Http\Request;

/**
 * 作品
 * Class WorksController
 * @author wsl
 * @package App\Http\Controllers\Admin
 */
class WorksController
{



    /**
     * 作品详情
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

        $detail = Works::query()->find($id);
        return Helper::responseJson($detail);
    }



    /**
     * 作品列表
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function list(Request $request)
    {
//        $lessonName = $request->input('lesson_name', '');
//        $applicableAge = $request->input('applicable_age', '');

        $query = Works::query()->where('is_show', 1);
//        if (!empty($lessonName)) {
//            $query->where('lesson_name', 'like', '%' . $lessonName . '%');
//        }
//        if (!empty($applicableAge)) {
//            $query->where('applicable_age', $applicableAge);
//        }

        $list = $query->get();
        if ($list) {
            $list = $list->toArray();
        }
        return Helper::responseJson($list);
    }


}