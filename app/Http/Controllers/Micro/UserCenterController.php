<?php


namespace App\Http\Controllers\Micro;


use App\Helpers\Helper;
use App\Models\Assess;
use App\Models\Lesson;
use App\Models\User;
use App\Models\UserLesson;
use Illuminate\Http\Request;


class UserCenterController
{

    /**
     * 用户中心
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function index(Request $request)
    {
        $accessToken = $request->input('access_token', '');

        if (empty($accessToken)) {
            return Helper::responseJson([], 0, '缺少accessToken');
        }
        $user = User::query()->where('access_token', $accessToken)->first();
        if (empty($user)) {
            return Helper::responseJson([], 0, '该用户不存在');
        }
        $userId = $user->user_id;
        $currentTime = date('Y-m-d H:i:s', time());

        //已结束课程
        $hadFinishLessonCount = UserLesson::query()->where('user_id', $userId)->where('end_time', '<', $currentTime)->count();

        //未开始课程
        $notStartLessonCount = UserLesson::query()->where('user_id', $userId)->where('start_time', '>', $currentTime)->count();

        $assessCount = Assess::query()->where('user_id', $userId)->count();

        $returnData = [
            'user' => $user,
            'had_finish_lesson_count' => $hadFinishLessonCount,
            'not_start_lesson_count' => $notStartLessonCount,
            'assess_count' => $assessCount
        ];

        return Helper::responseJson($returnData);
    }

    /**
     * 我的课程列表
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function myLessonList(Request $request)
    {
        $accessToken = $request->input('access_token', '');

        $lessonStatus = $request->input('lesson_status', '');

        if (empty($accessToken)) {
            return Helper::responseJson([], 0, '缺少accessToken');
        }
        $user = User::query()->where('access_token', $accessToken)->first();
        if (empty($user)) {
            return Helper::responseJson([], 0, '该用户不存在');
        }
        $userId = $user->user_id;
        $currentTime = date('Y-m-d H:i:s', time());
        //已结束
        if (1 == $lessonStatus) {
            $lessonIds = UserLesson::query()
                ->where('user_id', $userId)
                ->where('end_time', '<', $currentTime)
                ->pluck('lesson_id');
        } elseif (2 == $lessonStatus) { // 未开始
            $lessonIds = UserLesson::query()
                ->where('user_id', $userId)
                ->where('start_time', '>', $currentTime)
                ->pluck('lesson_id');
        } else {
            $lessonIds = UserLesson::query()
                ->where('user_id', $userId)
                ->pluck('lesson_id');
        }
        $lessonList = [];
        if ($lessonIds) {
            $lessonList = Lesson::query()
                ->whereIn('lesson_id', $lessonIds)
                ->get();

        }
        return Helper::responseJson($lessonList);
    }

    /**
     * 取消我的课程
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function myLessonCancel(Request $request)
    {
        $accessToken = $request->input('access_token', '');
        $lessonId = $request->input('lesson_id', '');
        if (empty($accessToken)) {
            return Helper::responseJson([], 0, '缺少accessToken');
        }
        if (empty($lessonId)) {
            return Helper::responseJson([], 0, '缺少lesson_id');
        }
        $user = User::query()->where('access_token', $accessToken)->first();
        if (empty($user)) {
            return Helper::responseJson([], 0, '该用户不存在');
        }
        $userId = $user->user_id;

        // todo 时间校验
        $res = UserLesson::query()->where('user_id', $userId)->where('lesson_id', $lessonId)->delete();
        if (false === $res) {
            return Helper::responseJson([], 0, '取消课程失败');
        }
        return Helper::responseJson([], 1, '取消课程成功');
    }


}