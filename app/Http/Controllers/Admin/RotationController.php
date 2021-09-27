<?php


namespace App\Http\Controllers\Admin;


use App\Constants\RotationConstant;
use App\Helpers\Helper;
use App\Models\RotationImage;
use Illuminate\Http\Request;

class RotationController
{

    /**
     * 创建轮播图
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function save(Request $request)
    {
        $type = $request->input('type', '');
        $images = $request->input('images', '');

        if (empty($type)) {
            return Helper::responseJson([], 0, '请确认该图所属类型');
        }
        $type = strtoupper($type);

        if (empty($images) || !is_array($images)) {
            return Helper::responseJson([], 0, '请确认图片是数组格式');
        }
        $insertData = [];
        foreach ($images as $image) {
            $tmp = [
                'image' => $image,
                'type' => $type
            ];
            $insertData[] = $tmp;
        }

        RotationImage::query()->where('type', $type)->delete();
        $result = RotationImage::query()->insert($insertData);
        if (false === $result) {
            return Helper::responseJson([], 0, '创建失败');
        }
        return Helper::responseJson([], 1, '创建成功');
    }



    public function list(Request $request)
    {
        $type = $request->input('type', RotationConstant::TYPE_HOME);
        $type = strtoupper($type);

        $list = RotationImage::query()
            ->where('type', $type)
            ->pluck('image');
        $imageList = [
            'image_list' => $list
        ];
        return Helper::responseJson($imageList);
    }


}