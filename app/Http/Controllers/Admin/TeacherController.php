<?php


namespace App\Http\Controllers\Admin;


use App\Helpers\Helper;
use App\Models\Lesson;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController
{

    /**
     * 添加教师
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function create(Request $request)
    {
        $teacherName =  $request->input('teacher_name', '');
        $teacherAge = $request->input('teacher_age', '');
        $honoraryTitle = $request->input('honorary_title', 0);
        $teacherDescription = $request->input('teacher_description', '');
        $teacherImage = $request->input('teacher_image', '');
        $avatar = $request->input('avatar', '');

        if (empty($teacherName)) {
            return Helper::responseJson([], 0, '缺少教师名称');
        }

        $model = new Teacher();
        $model->teacher_name = $teacherName;
        $model->teacher_age = $teacherAge;
        $model->honorary_title = $honoraryTitle;
        $model->teacher_description = $teacherDescription;
        $model->created_at = time();
        $model->teacher_image = $teacherImage;
        $model->avatar = $avatar;
        $res = $model->save();
        if (false == $res) {
            return Helper::responseJson([], 0, '创建失败');
        }
        return Helper::responseJson([], 1, '创建成功');
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


    public function edit(Request $request)
    {
        $id = $request->input('id', 0);
        $teacherName =  $request->input('teacher_name', '');
        $teacherAge = $request->input('teacher_age', '');
        $honoraryTitle = $request->input('honorary_title', 0);
        $teacherDescription = $request->input('teacher_description', '');
        $teacherImage = $request->input('teacher_image', '');
        $avatar = $request->input('avatar', '');

        if (empty($id)) {
            return Helper::responseJson([], 0, '缺少教师ID');
        }

        if (empty($teacher_name)) {
            return Helper::responseJson([], 0, '缺少教师名称');
        }

        $model = Teacher::query()->where('teacher_id', $id)->first();
        if (!empty($teacherName)) {
            $model->teacher_name = $teacherName;
        }
        if (!empty($teacherAge)) {
            $model->teacher_age = $teacherAge;
        }
        if (!empty($honoraryTitle)) {
            $model->honorary_title = $honoraryTitle;
        }
        if (!empty($teacherDescription)) {
            $model->teacher_description = $teacherDescription;
        }
        if (!empty($teacherImage)) {
            $model->teacher_image = $teacherImage;
        }
        if (!empty($avatar)) {
            $model->avatar = $avatar;
        }

        $model->updated_at = time();
        $res = $model->save();
        if (false == $res) {
            return Helper::responseJson([], 0, '编辑失败');
        }
        return Helper::responseJson([], 1, '编辑成功');
    }


    public function list(Request $request)
    {
        $teacherName = $request->input('teacher_name', '');
        $honoraryTitle = $request->input('honorary_title', '');

        $query = Teacher::query();
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


    public function del(Request $request)
    {
        $id = $request->input('id', '');
        if (empty($id)) {
            return Helper::responseJson([], 0, '缺少ID');
        }

        $res = Teacher::query()->where('teacher_id', $id)->delete();
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

        $res = Teacher::query()->where('teacher_id', $id)->update(['is_show' => $isShow]);
        if ($res) {
            return Helper::responseJson([], 1, '更新成功');
        }
        return Helper::responseJson([], 0, '更新失败');

    }
}