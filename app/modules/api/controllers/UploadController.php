<?php
namespace Wdxr\Modules\Api\Controllers;

use Phalcon\Exception;
use Wdxr\Models\Repositories\Attachment;
use Wdxr\Models\Repositories\Attachments;
use Wdxr\Models\Services\Cos;

class UploadController extends ControllerBase
{

    public function uploadOneAction()
    {
        try {
            $type = $this->request->getPost('type');
            if(!$type) {
                throw new Exception('请指定文件分类');
            }
            if ($this->request->hasFiles()) {
                $files = $this->request->getUploadedFiles();
                $file_name = [];
                $base_path = BASE_PATH."/files/$type/".date('Ymd')."/";
                if(file_exists($base_path) === false) {
                    if(mkdir($base_path, 0755, true) === false) {
                        throw new Exception('公司资料文件夹创建失败,上传失败');
                    }
                }
                foreach ($files as $file) {
                    $name =  self::getFileName().'.'.$file->getExtension(); $path = $base_path . $name;
                    $object = $type."/".date('Ymd')."/". $name;
                    $file->moveTo($path); self::uploadOSS($object, $path);
                    $file_name[] = Attachments::newAttachment($file->getName(), $file->getSize(), $path, $object);
                }
                //修改头像
                if($type == 'head'){
                    (new ToolsController())->setPicAction($file_name[0]);
                }
                return $this->json(self::RESPONSE_OK, $file_name, '上传成功');
            }
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    public function uploadSaveAction()
    {
        try {
            $type = $this->request->getPost('type');
            $object = $this->request->getPost('key');
            if(!$object){
                throw new Exception('上传失败');
            }
            if(!$type) {
                throw new Exception('请指定文件分类');
            }
            $file_id = Attachments::addAttachment($object);
            //修改头像
            if($type == 'head'){
                (new ToolsController())->setPicAction($file_id);
            }
            return $this->json(self::RESPONSE_OK, $file_id, '上传成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    public function upload_base64Action()
    {
        try {
            $type = $this->request->getPost('type');
            $object = $this->request->getPost('key');
            if (!$object) {
                throw new Exception('上传失败');
            }
            if (!$type) {
                throw new Exception('请指定文件分类');
            }
            $file_id = Attachments::addAttachment($object);
            //修改头像
            if ($type == 'head') {
                (new ToolsController())->setPicAction($file_id);
            }
            $url = Attachment::getAttachmentUrl($file_id);
            if (isset($url[0])) {
                $object = file_get_contents($url[0]);
                $base64 = base64_encode($object);
            } else {
                throw new Exception("文件路径获取失败");
            }

            return $this->json(self::RESPONSE_OK, $base64, '上传成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    static public function uploadOSS($object, $file)
    {
/*        $client = \OSS\Common::getOssClient();
        if(is_null($result = $client->uploadFile(\OSS\Common::getBucketName(), $object, $file))) {
            throw new Exception('上传到OSS失败');
        }
        return $result;*/
        $client = new Cos();
        if(!$result = $client->private_upload($file,$object)){
            throw new Exception('上传到COS失败');
        }
        return $result;
    }

    /*static public function uploadContractOSS($object, $file)
    {
        $client = \OSS\Common::getOssClient();
        if(is_null($result = $client->uploadFile(\OSS\Common::contract_bucket, $object, $file))) {
            throw new Exception('上传到OSS失败');
        }
        return $result;
    }*/


}