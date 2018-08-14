<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/28/028
 * Time: 10:55
 */

namespace Wdxr\Models\Services;

use Wdxr\Models\Repositories\Attachments;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;

class QrcodeService extends Services
{

    /**
     * @var string 二维码logo
     */
    protected static $logo;

    /**
     * @var string 指令
     */
    protected static $instruction='view_company_info';

    /**
     * @var array 允许的指令数组
     */
    protected static $instructionArray = ['view_company_info'];

    /**
     * @var array  二维码参数
     */
    protected static $param = [];

    /**
     * @var int 二维码到期时间
     */
    protected static $expires = 0;

    /**
     * @var string 二维码容错级别
     */
    protected static $errorCorrectionLevel = 'L';

    /**
     * @var int 二维码大小
     */
    protected static $matrixPointSize = 6;

    /**
     * @var int|mixed 二维码边缘大小
     */
    protected static $margin = 4;

    /**
     * @param $level
     * @return $this
     */
    function setLevel($level)
    {
        self::$errorCorrectionLevel = $level;
        return $this;
    }

    /**
     * @param $size
     * @return $this
     */
    function setSize($size)
    {
        self::$matrixPointSize = $size;
        return $this;
    }

    /**
     * @param $margin
     * @return $this
     */
    function setMargin($margin)
    {
        self::$margin = $margin;
        return $this;
    }

    /**
     * 生成二维码
     * @param bool $instruction
     * @param array $param
     * @param bool $logo
     * @param bool $expires 过期时间以秒为单位
     * @return resource|string
     */
    static public function makeQrcode($instruction=false,$param=[],$logo=false,$expires=false)
    {

        self::$instruction = $instruction?:self::$instruction;
        self::$param = $param?:self::$param;
        self::$expires = $expires?:self::$expires;

        $param_str = implode('/',self::$param);
        $param_strs = $param_str ? '/'.$param_str : '';

        if(self::$expires){         //添加过期时间
            self::$expires = time()+self::$expires;
        }
        $data = self::$instruction.'/'.self::$expires.$param_strs;
        //加密信息
        $qr_data = base64_encode(~$data);

        $file_name = md5(time().rand(100000,999999)).'.png';
        $logo_file_name = 'logo_'.$file_name;
        $qr_dir = 'qrcode/'.self::$instruction.'/'.date('Ymd').'/';
        $dir = BASE_PATH.'/files/'.$qr_dir;
        self::mkDirs($dir);
        $QR = $dir.$file_name;

        \QRcode::png($qr_data,$QR,self::$errorCorrectionLevel,self::$matrixPointSize,self::$margin);

        //添加logo
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));//var_dump($QR);die;
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
            imagepng($QR, $dir.$logo_file_name);   //保存加logo图片地址

            $path = $dir.$logo_file_name;
            $oss_object = $qr_dir.$logo_file_name;
        }else{
            $path = $QR;
            $oss_object = $qr_dir.$file_name;
        }

        self::uploadOSS($oss_object, $path);
        return Attachments::newAttachment($file_name, filesize($path), $path, $oss_object);
    }

    /**
     * 判断目录是否存在
     * @param $dir
     * @param int $mode
     * @return bool
     */
    static private function mkDirs($dir, $mode = 0777)
    {

        if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
        if (!self::mkDirs(dirname($dir), $mode)) return FALSE;
        return @mkdir($dir, $mode);
    }

    /**
     * 上传OSS文件
     * @param $object
     * @param $file
     * @return null
     * @throws InvalidServiceException
     */
    static public function uploadOSS($object, $file)
    {
        $client = \OSS\Common::getOssClient();
        if(is_null($result = $client->uploadFile(\OSS\Common::getBucketName(), $object, $file))) {
            throw new InvalidServiceException('上传到OSS失败');
        }
        return $result;
    }

    /**
     * 处理二维码信息
     * @param $data
     * @return bool
     * @throws InvalidServiceException
     */
    static public function scanQrcode($data)
    {
        $qr_data = ~base64_decode($data);

        $data_array = explode('/',$qr_data);
        $instruction = array_shift($data_array);

        if(!in_array($instruction,self::$instructionArray)){
            throw new InvalidServiceException("指令参数异常");
        }
        self::$instruction = $instruction ? : self::$instruction;

        //判断是否过期
        $expires = array_shift($data_array);    //过期时间
        if($expires && $expires < time()){
            throw new InvalidServiceException("二维码已过期");
        }

        $command_arr = explode('_', self::$instruction);
        $command = array_shift($command_arr);
        $class = 'Wdxr\Models\Services\\';
        foreach ($command_arr as $item) {
            $class .= ucfirst($item);
        }
        if(class_exists($class) === false) {
            throw new InvalidServiceException("请求的类不存在");
        }
        $object = new $class();
        if(method_exists($object, $command) === false) {
            throw new InvalidServiceException("请求的方法不存在");
        }

        $params = implode(',',$data_array);

        $result = false;
        eval("\$result = \$object->\$command(\$params);");
        return $result;
    }
}