<?php
namespace Wdxr\Models\Services;
use QCloud\Cos\Api;
use QCloud\Cos\Auth;

class Cos extends Services
{
    /**
     * @var Api
     */
    protected static $instance;
    protected static $auth_instance;

    const BUCKET_ATTACHMENT = 'attachment';
    const BUCKET_PUBLIC = 'public';
    const BUCKET_CONTRACT = 'contract';

    public $domain = [
        'attachment'        => 'https://cdn.file.guanjia16.net',
        'public'            => 'http://cdn.static.guanjia16.com',
        'contract'          => 'http://contract-1253678897.cosbj.myqcloud.com',
    ];

    /**
     * @return Api
     */
    public static function getCosInstance()
    {
        if (isset(self::$instance) === false) {
            $qcloud = self::getStaticConfig()->qcloud;
            $config = array(
                'app_id' => $qcloud->app_id,
                'secret_id' => $qcloud->secret_id,
                'secret_key' => $qcloud->secret_key,
                'region' => 'bj',   // bucket所属地域：华北 'tj' 华东 'sh' 华南 'gz'
                'timeout' => 300
            );
            self::$instance = new Api($config);
        }
        return self::$instance;
    }

    /**
     * @return Auth
     */
    public static function getAuthInstance()
    {
        if (isset(self::$auth_instance) === false) {
            $qcloud = self::getStaticConfig()->qcloud;
            self::$auth_instance = new Auth($qcloud->app_id, $qcloud->secret_id, $qcloud->secret_key);
        }
        return self::$auth_instance;
    }

    public static function getSignature($expire, $bucket = self::BUCKET_ATTACHMENT)
    {
        $auth = self::getAuthInstance();
        return $auth->createReusableSignature($expire + time(), $bucket);
    }

    /**
     * 上传文件到指定目录
     * @param $bucket
     * @param $src
     * @param $dst
     * @return array|mixed
     */
    public function upload($bucket, $src, $dst)
    {
        return self::getCosInstance()->upload($bucket, $src, $dst);
    }

    /**
     * 上传私有文件
     * @param $src
     * @param $dst
     * @return array|mixed
     */
    public function private_upload($src, $dst)
    {
        return $this->upload(self::BUCKET_ATTACHMENT, $src, $dst);
    }

    /**
     * 上传公共文件
     * @param $src
     * @param $dst
     * @return array|mixed
     */
    public function public_upload($src, $dst)
    {
        return $this->upload(self::BUCKET_PUBLIC, $src, $dst);
    }

    /**
     * 下载文件到指定目录
     * @param $bucket
     * @param $src
     * @param $dst
     * @return array
     */
    public function download($bucket, $src, $dst)
    {
        return self::getCosInstance()->download($bucket, $src, $dst);
    }

    /**
     * 删除文件
     * @param $bucket
     * @param $dst
     * @return array|mixed
     */
    public function deleteFile($bucket, $dst)
    {
        return self::getCosInstance()->delFile($bucket, $dst);
    }

    /**
     * 生成文件URL
     * @param $bucket
     * @param $dst
     * @param int $expired
     * @return array|bool
     */
    public function url($bucket, $dst, $expired = 60)
    {
        self::getCosInstance()->setDomain($this->domain[$bucket]);
        return self::getCosInstance()->getDomainUrl($bucket, $dst, $expired);
    }

    /**
     * 生成私有文件访问URL
     * @param $dst
     * @param int $expire
     * @return bool|string
     */
    public function private_url($dst, $expire = 10)
    {
        self::getCosInstance()->setDomain($this->domain[self::BUCKET_ATTACHMENT]);
        return self::getCosInstance()->getDomainUrl(self::BUCKET_ATTACHMENT, $dst, $expire);
    }

    /**
     * 生成公共访问URL
     * @param $dst
     * @return bool|string
     */
    public function public_url($dst)
    {
        self::getCosInstance()->setDomain($this->domain[self::BUCKET_ATTACHMENT]);
        return self::getCosInstance()->getDomainUrl(self::BUCKET_ATTACHMENT, $dst, false);
    }

    /**
     * 获取文件信息
     * @param $bucket
     * @param $path
     * @return array|mixed
     */
    public function stat($bucket, $path)
    {
        return self::getCosInstance()->stat($bucket, $path);
    }


}