<?php


namespace App\Http\Controllers\Admin;


use App\Helpers\Helper;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController
{

    /**
     * 课程创建
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function create(Request $request)
    {
        $lessonCategoryId =  $request->input('lesson_category_id', 0);
        $lessonName = $request->input('lesson_name', '');
        $applicableAge = $request->input('applicable_age', 0);
        $lessonDescription = $request->input('lesson_description', '');
        $lessonImage = $request->input('lesson_image', '');


        if (empty($lessonCategoryId)) {
            return Helper::responseJson([], 0, '缺少课程分类ID');
        }

        if (empty($lessonName)) {
            return Helper::responseJson([], 0, '缺少课程名称');
        }

        $model = new Lesson();
        $model->lesson_category_id = $lessonCategoryId;
        $model->lesson_name = $lessonName;
        $model->applicable_age = $applicableAge;
        $model->lesson_description = $lessonDescription;
        $model->lesson_image = $lessonImage;
        $model->created_at = time();
        $res = $model->save();
        if (false === $res) {
            return Helper::responseJson([], 0, '创建失败');
        }
        return Helper::responseJson([], 1, '创建成功');
    }

    /**
     * 详情
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

    /**
     * 课程编辑
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function edit(Request $request)
    {
        $id = $request->input('id', 0);
        $lesson_category_id = $request->input('lesson_category_id', 0);
        $lesson_name = $request->input('lesson_name', '');
        $applicable_age = $request->input('applicable_age', 0);
        $lesson_description = $request->input('lesson_description', '');
        $lesson_image = $request->input('lesson_image', '');


        if (empty($id)) {
            return Helper::responseJson([], 0, '缺少课程ID');
        }

        if (empty($lesson_category_id)) {
            return Helper::responseJson([], 0, '缺少课程分类ID');
        }

        if (empty($lesson_name)) {
            return Helper::responseJson([], 0, '缺少课程名称');
        }


        $model = Lesson::query()->where('lesson_id', $id)->first();
        $model->lesson_category_id = $lesson_category_id;
        $model->lesson_name = $lesson_name;
        $model->applicable_age = $applicable_age;
        $model->lesson_description = $lesson_description;
        $model->lesson_image = $lesson_image;
        $model->updated_at = time();
        $res = $model->save();
        if (false == $res) {
            return Helper::responseJson([], 0, '编辑失败');
        }
        return Helper::responseJson([], 1, '编辑成功');

    }

    /**
     * 课程列表
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function list(Request $request)
    {
        $lessonName = $request->input('lesson_name', '');
        $applicableAge = $request->input('applicable_age', '');

        $query = Lesson::query();
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

    /**
     * 课程删除
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function del(Request $request)
    {
        $id = $request->input('id', '');
        if (empty($id)) {
            return Helper::responseJson([], 0, '缺少ID');
        }

        $res = Lesson::query()->where('lesson_id', $id)->delete();
        if ($res) {
            return Helper::responseJson([], 1, '删除成功');
        }
        return Helper::responseJson([], 0, '删除失败');
    }


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

        $res = Lesson::query()->where('lesson_id', $id)->update(['is_show' => $isShow]);
        if ($res) {
            return Helper::responseJson([], 1, '更新成功');
        }
        return Helper::responseJson([], 0, '更新失败');

    }
}