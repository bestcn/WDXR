<?php
namespace Wdxr\Modules\Api\Controllers;

use Phalcon\Exception;
use Phalcon\Mvc\Controller;
use Wdxr\Models\Repositories\Attachments;
use Phalcon\Logger\Adapter\File as FileAdapter;

class ControllerBase extends Controller
{

    const RESPONSE_OK = 1;
    const RESPONSE_FAILED = 0;
    const RESPONSE_CONFUSE = 2;

    static private $_logger;

    protected function json($status, $data, $info, $options = 0)
    {
        $this->response->setContentType('application/json');
        return $this->response->setJsonContent(['status' => $status, 'data' => $data, 'info' => trim($info)], $options);
    }

    public function upload($company_id, $type)
    {
        if ($this->request->hasFiles()) {
            $files = $this->request->getUploadedFiles();
            $file_name = [];
            $base_path = BASE_PATH."/files/company/".$company_id . "/".$type."/";
            if(file_exists($base_path) === false) {
                if(mkdir($base_path, 0755, true) === false) {
                    throw new Exception('公司资料文件夹创建失败,上传失败');
                }
            }
            foreach ($files as $file) {
                $path = $base_path . self::getFileName().'.'.$file->getExtension();
                $file->moveTo($path);
                $file_name[] = Attachments::newAttachment($file->getName(), $file->getSize(), $path);
            }
            return $file_name;
        }
        return false;
    }

    public function logger()
    {
        if(is_null(self::$_logger)) {
            $name = BASE_PATH."/cache/logs/".date("Ymd").".log";
            self::$_logger = new FileAdapter($name);
        }
        return self::$_logger;
    }

    static protected function getFileName()
    {
        return md5(time().random_int(1000, 9999));
    }

}
