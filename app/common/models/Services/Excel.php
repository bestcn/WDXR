<?php
namespace Wdxr\Models\Services;

use Wdxr\Models\Repositories\Attachments;
use Wdxr\Models\Repositories\UserAdmin;

class Excel extends \PHPExcel
{
    static private $i = 1;
    static private $j = 65;

    /**
     * @return Excel
     */
    public static function create()
    {
        $objPHPExcel = new Excel();
        $objPHPExcel->getProperties()->setCreator("WDXR")
            ->setLastModifiedBy("WDXR")
            ->setTitle("报销财务报表")
            ->setSubject("报销财务报表")
            ->setDescription("WDXR财务报表.")
            ->setKeywords("WDXR")
            ->setCategory("财务报表");
        return $objPHPExcel;
    }

    //导出普惠调查报告
    public static function loanPresentation($data)
    {
        $phpexcel =\PHPExcel_IOFactory::createReader("Excel2007")->load(BASE_PATH."/public/apl2.xlsx");
        //$objWriter = new \PHPExcel_Writer_Excel2007($phpexcel);
        $objWriter = \PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
        $objProps = $phpexcel->getProperties();
        $objProps->setCreator("WDXR");
        $objProps->setLastModifiedBy("WDXR");
        $objProps->setTitle("贷款调查报告");
        $objProps->setSubject("贷款调查报告");
        $objProps->setDescription("WDXR贷款调查报告");
        $objProps->setKeywords("WDXR");
        $objProps->setCategory("贷款调查报告");
        $phpexcel->setActiveSheetIndex(0);
        $objActSheet = $phpexcel->getActiveSheet();
        $objActSheet->setTitle('贷款调查报告');
        $objActSheet->setCellValue ( 'C3', $data['name'] );
        $objActSheet->setCellValue ( 'G3', ' '.$data['license'] );
        $objActSheet->setCellValue ( 'C4', ' '.$data['identity'] );
        $objActSheet->setCellValue ( 'C5', $data['address'] );
        $objActSheet->setCellValue ( 'G5', $data['tel'] );
        $objActSheet->setCellValue ( 'C6', $data['money'] );
        $objActSheet->setCellValue ( 'G6', $data['term'] );
        $objActSheet->setCellValue ( 'C8', $data['system_loan'] );
        $objActSheet->setCellValue ( 'E8', $data['sponsion'] );
        $objActSheet->setCellValue ( 'C9', $data['other_loan'] );
        $objActSheet->setCellValue ( 'E9', $data['unhealthy'] );
        $objActSheet->setCellValue ( 'C11', $data['last_year'] );
        $objActSheet->setCellValue ( 'E12', $data['quota'] );
        $objActSheet->setCellValue ( 'C13', $data['this_year'] );
        $objActSheet->setCellValue ( 'E13', ' '.$data['remarks'] );
        $outputFileName = $data['name'].'贷款调查报告'.time().".xlsx";
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outputFileName.'"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    //导出普惠申请表
    public static function loanApply($data)
    {
        $phpexcel =\PHPExcel_IOFactory::createReader("Excel2007")->load(BASE_PATH."/public/apl1.xlsx");
        //$objWriter = new \PHPExcel_Writer_Excel2007($phpexcel);
        $objWriter = \PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
        $objProps = $phpexcel->getProperties();
        $objProps->setCreator("WDXR");
        $objProps->setLastModifiedBy("WDXR");
        $objProps->setTitle("贷款申请表");
        $objProps->setSubject("贷款调申请表");
        $objProps->setDescription("WDXR贷款申请表");
        $objProps->setKeywords("WDXR");
        $objProps->setCategory("贷款申请表");
        $phpexcel->setActiveSheetIndex(0);
        $objActSheet = $phpexcel->getActiveSheet();
        $objActSheet->setTitle('贷款申请表');
        $objActSheet->setCellValue ( 'C2', $data['name'] );
        $objActSheet->setCellValue ( 'E2', $data['sex'] );
        $objActSheet->setCellValue ( 'H2', ' '.$data['identity'] );
        $objActSheet->setCellValue ( 'C3', $data['address'] );
        $objActSheet->setCellValue ( 'C4', $data['money'] );
        $objActSheet->setCellValue ( 'H4', $data['term'] );
        $objActSheet->setCellValue ( 'A6', $data['business'] );
        $objActSheet->setCellValue ( 'C19', $data['tel'] );
        $objActSheet->setCellValue ( 'C20', $data['time'] );
        $outputFileName = $data['name'].'贷款申请表'.time().".xlsx";
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outputFileName.'"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    public function title($title)
    {
        $sheet = $this->setActiveSheetIndex(0);

        $chr = chr(65).self::$i++;
        $sheet->setCellValue($chr, $title);

        return $this;
    }

    public function header(array $titles)
    {
        $sheet = $this->setActiveSheetIndex(0);

        $asc = self::$j;
        foreach ($titles as $title)
        {
            $chr = chr($asc++).self::$i;
            $sheet->setCellValue($chr, $title);
//            $asc++;
        }
        self::$i++;
        return $this;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function value(array $values)
    {
        $sheet = $this->setActiveSheetIndex(0);
        $chr = chr(self::$j).self::$i;
        foreach ($values as $value)
        {
            $asc = self::$j;
            foreach ($value as $item)
            {
                $chr = chr($asc++).self::$i;
                $sheet->setCellValue($chr, ' '.$item);
            }
            self::$i++;
        }
        $sheet->calculateColumnWidths();
        $styleThinBlackBorderOutline = array(
            'borders' => array (
                'outline' => array (
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,   //设置border样式
                    'color' => array ('argb' => 'FF000000'),          //设置border颜色
                ),
                'inside' => [
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,   //设置border样式
                    'color' => array ('argb' => 'FF000000'),          //设置border颜色
                ]
            ),
        );
        $sheet->getStyle('A1:'.$chr)->applyFromArray($styleThinBlackBorderOutline);

        return $this;
    }

    /**
     * @param $title
     * @return Excel
     */
    public function sheetTitle($title)
    {
        $this->getActiveSheet()->setTitle($title);

        return $this;
    }

    /**
     * @param $filename
     */
    public function output($filename)
    {
        $this->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($this, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    public function download($filename)
    {
        $this->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $filename = $filename.'-'.date('YmdHis', time()).".xlsx";
        $src_path = BASE_PATH."/files/excel/".$filename;
        $dst_path = 'excel/'.date('Ymd')."/".$filename;

        $objWriter = \PHPExcel_IOFactory::createWriter($this, 'Excel2007');
        $objWriter->save($src_path);

        $result = (new Cos)->private_upload($src_path, $dst_path);
        if ($result['message'] == 'SUCCESS') {
            $admin_id = Services::getStaticDi()->get('auth')->auth->getIdentity()['id'];
            $device_id = UserAdmin::getDeviceId($admin_id, UserAdmin::TYPE_ADMIN);
            return Attachments::newAttachment($filename, filesize($filename), $src_path, $dst_path, $device_id);
        }
        return false;
    }


}