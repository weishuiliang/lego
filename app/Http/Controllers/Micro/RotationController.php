<?php


namespace App\Http\Controllers\Micro;

use App\Constants\RotationConstant;
use App\Helpers\Helper;
use App\Models\RotationImage;
use Illuminate\Http\Request;


/**
 * Class RotationController
 * @author wsl
 * @package App\Http\Controllers\Micro
 */
class RotationController
{

    /**
     * list
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
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