<?php


namespace App\Http\Controllers\Micro;


use App\Helpers\Helper;
use App\Libs\MicroLib;
use App\Models\User;
use Illuminate\Http\Request;

class UserController
{
    /**
     * 使用code进行授权
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @see https://developers.weixin.qq.com/miniprogram/dev/framework/open-ability/login.html
     */
    public function login(Request $request)
    {
        $code = $request->input('code', '');
        if (empty($code)) {
            return Helper::responseJson([], 0, '缺少code');
        }

        try {
            $micro = new MicroLib();
            $res = $micro->login($code);

            if (!empty($res)) {
                $userModel = new User();
                $userModel->openid = $res['openid'];
                $userModel->session_key = $res['session_key'];
                $userModel->access_token = uniqid();
                $userModel->save();
            }

            return Helper::responseJson($res);
        } catch (\Exception $e) {
            return Helper::responseJson([], 0, $e->getMessage());
        }
    }

    /**
     * decryptData
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function decryptData(Request $request)
    {
        $accessToken = $request->input('access_token', '');
        $encryptedData = $request->input('encryptedData', '');
        $iv = $request->input('iv', '');

        if (empty($accessToken)) {
            return Helper::responseJson([], 0, '缺少accessToken');
        }
        if (empty($encryptedData)) {
            return Helper::responseJson([], 0, '缺少加密数据encryptedData');
        }
        if (empty($iv)) {
            return Helper::responseJson([], 0, '缺少iv');
        }
        $user = User::query()->where('access_token', $accessToken)->first();
        if (empty($user)) {
            return Helper::responseJson([], 0, '未找到该用户或access_token已过期');
        }
        $sessionKey = $user->session_key;

        $microLib = new MicroLib();
        $errorCode = $microLib->decryptData($sessionKey, $encryptedData, $iv, $data);
        //$data 是个json
        if (0 === $errorCode) {
            return Helper::responseJson( json_decode($data, true));
        }
        return Helper::responseJson([], 0, $errorCode);
    }

    /***
     * 保存用户信息
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function save(Request $request)
    {
        $accessToken = $request->input('access_token', '');
        $userName = $request->input('user_name', '');
        $avatar = $request->input('avatar', '');
        $gender = $request->input('gender', 0);
        $province = $request->input('province', '');
        $city = $request->input('city', '');
        $country = $request->input('country', '');

        if (empty($accessToken)) {
            return Helper::responseJson([], 0, '缺少accessToken');
        }
        $user = User::query()->where('access_token', $accessToken)->first();
        if (empty($user)) {
            return Helper::responseJson([], 0, '未找到该用户或access_token已过期');
        }
        $user->user_name = $userName;
        $user->avatar = $avatar;
        $user->gender = $gender;
        $user->province = $province;
        $user->city = $city;
        $user->country = $country;
        $saveResult = $user->save();

        if (false === $saveResult) {
            return Helper::responseJson([], 0, '保存失败');
        }
        return Helper::responseJson([], 1, '保存成功');
    }

    /**
     * 用户信息
     *
     * @author wsl
     */
    public function info(Request $request)
    {
        $accessToken = $request->input('access_token', '');

        if (empty($accessToken)) {
            return Helper::responseJson([], 0, '缺少accessToken');
        }
        $user = User::query()->where('access_token', $accessToken)->first();
        if (empty($user)) {
            return Helper::responseJson([], 0, '未找到该用户或access_token已过期');
        }
        return Helper::responseJson($user);
    }

    /**
     * 用户编辑
     *
     * @author wsl
     * @param Request $request
     * @return Helper
     */
    public function edit(Request $request)
    {
        $accessToken = $request->input('access_token', '');
        $userName = $request->input('user_name', '');
        $mobile = $request->input('mobile', '');
        $childName = $request->input('child_name', '');
        $childPetName = $request->input('child_pet_name', '');
        $avatar = $request->input('avatar', '');

        if (empty($accessToken)) {
            return Helper::responseJson([], 0, '缺少accessToken');
        }
        $user = User::query()->where('access_token', $accessToken)->first();
        if (!empty($userName)) {
            $user->user_name = $userName;
        }
        if (!empty($mobile)) {
            $user->mobile = $mobile;
        }
        if (!empty($childName)) {
            $user->child_name = $childName;
        }
        if (!empty($childPetName)) {
            $user->child_pet_name = $childPetName;
        }
        if (!empty($avatar)) {
            $user->avatar = $avatar;
        }
        $saveResult = $user->save();

        if (false === $saveResult) {
            return Helper::responseJson([], 0, '保存失败');
        }
        return Helper::responseJson([], 1, '保存成功');
    }



}