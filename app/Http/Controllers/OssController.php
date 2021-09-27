<?php


namespace App\Http\Controllers;


use App\Helpers\Helper;
use App\Libs\OssLib;
use Illuminate\Http\Request;
use OSS\Core\OssException;
use OSS\OssClient;

class OssController
{

    /**
     * 上传图片
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function uploadImage(Request $request)
    {
        $file = $request->file('file');
        $isValid = $file->isValid();
        if (false === $isValid) {
            return Helper::responseJson([], 0, '该文件不合法');
        }
        //文件后缀
        $fileExtension = $file->getClientOriginalExtension();
        //重新命名文件名称
        $fullName = date('YmdHis') . uniqid() . '.' . $fileExtension;
        //临时文件全路径
        $realpath = $file->getRealPath();

        $options = [
            'Content-Type' => "image/{$fileExtension}",
            'Content-Disposition' => "inline;filename={$fullName}"
        ];
        try {
            $oss = new OssLib();
            $url = $oss->uploadImage($fullName, $realpath, $options);

            return Helper::responseJson(['url' => $url]);
        } catch (\Exception $e) {
            return Helper::responseJson([], 0, '上传失败：' . $e->getMessage());
        }
    }





}