<?php


namespace App\Libs;


use App\Constants\ErrorCode;
use App\Models\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class MicroLib
{

    private $appid;

    private $secret;

    private $grant_type = 'authorization_code';

    public function __construct()
    {
        $this->appid = env('MICRO_APPID');
        $this->secret = env('MICRO_SECRET');
    }

    /**
     * login
     *
     * @author wsl
     * @param $code
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function login($code)
    {
        $appid = $this->appid;
        $secret = $this->secret;
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";
        try {
            $client = new Client();
            $response = $client->get($url);
            $result = json_decode($response->getBody()->getContents(), true);

            Log::saveLog('micro login', json_encode($result));

            if (isset($result['errcode'])) {
                throw new \Exception($result['errmsg']);
            }

            return $result;
        } catch (ClientException $e) {
            throw new \Exception("网络异常");
        } catch (\Exception $e) {
            throw new \Exception("授权异常");
        } catch (\Throwable $th) {
            throw new \Exception("授权异常");
        }
    }

    /**
     * 用sessionKey进行解密
     *
     * @param $encryptedData
     * @param $iv
     * @param $data
     * @return mixed
     * @author wsl
     */
    public function decryptData($sessionKey, $encryptedData, $iv, &$data)
    {
        if (strlen($sessionKey) != 24) {
//            return ErrorCode::$IllegalAesKey;
            return "sessionKey有误";
        }
        $aesKey = base64_decode($sessionKey);

        if (strlen($iv) != 24) {
//            return ErrorCode::$IllegalIv;
            return "Iv有误";
        }
        $aesIV = base64_decode($iv);

        $aesCipher = base64_decode($encryptedData);

        $result = openssl_decrypt($aesCipher,"AES-128-CBC", $aesKey,1, $aesIV);

        $dataObj = json_decode($result);
        if ($dataObj == NULL) {
//            return ErrorCode::$IllegalBuffer;
            return "解密出错";
        }
        if ($dataObj->watermark->appid != $this->appid) {
            return "非法解密";
//            return ErrorCode::$IllegalBuffer;
        }
        $data = $result;
        return ErrorCode::$OK;
    }
}