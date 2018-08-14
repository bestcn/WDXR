<?php
namespace Wdxr\Modules\Admin\Controllers;

use Phalcon\Exception;
use Phalcon\Mvc\View;
use Wdxr\Models\Repositories\Attachments;
use Wdxr\Models\Repositories\Repositories;
use Wdxr\Models\Services\Cos;

class ToolsController extends ControllerBase
{

    public function upload($type, $device_id)
    {
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
                if($file->getSize()) {
                    $name =  self::getFileName().'.'.$file->getExtension(); $path = $base_path . $name;
                    $object = $type."/".date('Ymd')."/". $name;
                    $file->moveTo($path); self::uploadOSS($object, $path);
                    $file_name[$file->getKey()] = Attachments::newAttachment($file->getName(), $file->getSize(), $path, $object, $device_id);
                }
            }
            return $file_name;
        }
        return false;
    }

    public function contractTemplateAction($name)
    {
        $this->view->enable();
        $this->view->disableLevel([
            View::LEVEL_MAIN_LAYOUT => true,
            View::LEVEL_AFTER_TEMPLATE => true,
            View::LEVEL_LAYOUT      => true,
            View::LEVEL_BEFORE_TEMPLATE => true,
        ]);
        $this->response->setContentType('text/html');
        $this->view->setVar('name', $name);
    }

    static public function uploadOSS($object, $file)
    {
/*                $client = \OSS\Common::getOssClient();
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

    static protected function getFileName()
    {
        return md5(time().random_int(1000, 9999));
    }

    public function get_statusAction()
    {
        $this->view->disable();
        if($this->request->isAjax()) {
            return $this->response->setJsonContent([
                ['id' => '1', 'name' => '正常'],
                ['id' => '2', 'name' => '禁用'],
            ]);
        }
        return '';
    }

    public function searchAction()
    {
        $search = $this->request->getQuery('top-search', 'trim', '');
        $numberPage = $this->request->get('page') ? : 1;

        if($search) {
            /**
             * @var $company \Wdxr\Models\Repositories\Company
             */
            $company = Repositories::getRepository('Company');
            $builder = $company->getCompanyList($search);
        } else {
            $builder = $this->modelsManager->createBuilder();
        }

        $pages = new \Phalcon\Paginator\Adapter\QueryBuilder([
            'builder' => $builder,
            'limit'=> 15,
            'page' => $numberPage
        ]);

        $this->view->setVars([
            'page' => $pages->getPaginate()
        ]);
    }

}