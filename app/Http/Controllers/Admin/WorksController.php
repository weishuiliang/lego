<?php


namespace App\Http\Controllers\Admin;

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
     * 作品创建
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function create(Request $request)
    {
        $worksImage =  $request->input('works_image', '');
        $title = $request->input('title', '');
        $tags = $request->input('tags', '');
        $userId = $request->input('user_id', 0);

        if (empty($worksImage)) {
            return Helper::responseJson([], 0, '缺少作品图片');
        }
        if (empty($title)) {
            return Helper::responseJson([], 0, '缺少作品标题');
        }
        if (empty($tags) || !is_array($tags)) {
            return Helper::responseJson([], 0, '缺少作品标签或不是数组');
        }
        if (empty($userId)) {
            return Helper::responseJson([], 0, '作品需关联用户');
        }

        $model = new Works();
        $model->works_image = $worksImage;
        $model->title = $title;
        $model->tags = $tags;
        $model->user_id = $userId;
        $model->date = date('m月d日', time());
        $model->created_at = time();
        $res = $model->save();
        if (false === $res) {
            return Helper::responseJson([], 0, '创建失败');
        }
        return Helper::responseJson([], 1, '创建成功');
    }

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
     * 作品编辑
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function edit(Request $request)
    {
        $id = $request->input('id', 0);
        $worksImage =  $request->input('works_image', '');
        $title = $request->input('title', '');
        $tags = $request->input('tags', '');
        $userId = $request->input('user_id', 0);

        if (empty($id)) {
            return Helper::responseJson([], 0, '缺少课程ID');
        }
        if (!empty($tags) && !is_array($tags)) {
            return Helper::responseJson([], 0, '作品标签不是数组');
        }

        $model = Works::query()->where('works_id', $id)->first();
        if (!empty($worksImage)) {
            $model->works_image = $worksImage;
        }
        if (!empty($title)) {
            $model->title = $title;
        }
        if (!empty($tags)) {
            $model->tags = $tags;
        }
        if (!empty($userId)) {
            $model->user_id = $userId;
        }
        $model->updated_at = time();
        $res = $model->save();
        if (false == $res) {
            return Helper::responseJson([], 0, '编辑失败');
        }
        return Helper::responseJson([], 1, '编辑成功');

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

        $query = Works::query();
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

    /**
     * 展示切换
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function show(Request $request)
    {
        $id = $request->input('id', '');
        $isShow = $request->input('is_show', null);

        if (empty($id)) {
            return Helper::responseJson([], 0, '缺少ID');
        }
        if (null === $isShow) {
            return Helper::responseJson([], 0, '缺少is_show');
        }

        $res = Works::query()->where('works_id', $id)->update(['is_show' => $isShow]);
        if ($res) {
            return Helper::responseJson([], 1, '更新成功');
        }
        return Helper::responseJson([], 0, '更新失败');

    }
}