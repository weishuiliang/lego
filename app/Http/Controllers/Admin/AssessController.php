<?php


namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Models\Assess;
use App\Models\Lesson;
use Illuminate\Http\Request;

/**
 * Class AssessmentController
 * 评测报告
 * @author wsl
 * @package App\Http\Controllers\Admin
 */
class AssessController
{

    /**
     * 评测创建
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function create(Request $request)
    {
        $assessImage =  $request->input('assess_image', '');
        $title = $request->input('title', '');
        $description = $request->input('description', '');
        $userId = $request->input('user_id', '');

        if (empty($assessImage)) {
            return Helper::responseJson([], 0, '请上传评测图片');
        }

        if (empty($title)) {
            return Helper::responseJson([], 0, '请填写评测标题');
        }

        if (empty($description)) {
            return Helper::responseJson([], 0, '请填写评测描述');
        }
        if (empty($userId)) {
            return Helper::responseJson([], 0, '请选择用户');
        }

        $model = new Assess();
        $model->user_id = $userId;
        $model->assess_image = $assessImage;
        $model->title = $title;
        $model->description = $description;
        $model->created_at = time();
        $res = $model->save();
        if (false === $res) {
            return Helper::responseJson([], 0, '创建失败');
        }
        return Helper::responseJson([], 1, '创建成功');
    }
}