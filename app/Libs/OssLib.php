<?php


namespace App\Libs;


use OSS\Core\OssException;
use OSS\OssClient;

class OssLib
{


    private $ossClient;

    private $accessKeyId;

    private $accessKeySecret;

    private $bucket;

    private $endpoint;


    public function __construct()
    {
        $this->accessKeyId = env('OSS_ACCESS_KEY_ID');
        $this->accessKeySecret = env('OSS_ACCESS_KEY_SECRET');
        $this->bucket = env('OSS_BUCKET');
        $this->endpoint = env('OSS_ENDPOINT');
//        $this->ossUrl = ::getConfig('OSS_URL');
    }

    /**
     * uploadImage
     *
     * @author wsl
     * @param $object
     * @param $filePath
     * @param $options
     * @return mixed
     * @throws \Exception
     */
    public function uploadImage($object, $filePath, $options = null)
    {
        try {
            $this->ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
            $result = $this->ossClient->uploadFile($this->bucket, $object, $filePath, $options);
//            if (isset($result['info']['url']) && !empty($result['info']['url'])) {
//                $content = file_get_contents(__FILE__);
//                $options = [
//                    OssClient::OSS_HEADERS => [
//                        'content-type' => 'image/png'
//                    ]
//                ];
//                $this->ossClient->putObject($this->bucket, $result['info']['url'], $content, $options);
//            }
            return $result['info']['url'];
        } catch (OssException $e) {
            throw new \Exception($e->getErrorMessage() . $e->getLine() . $e->getFile());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}