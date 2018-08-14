<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/6/16
 * Time: 9:23
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\ReportTerm as EntityReportTerm;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class ReportTerm
{

    /**
     * @param $id
     * @return EntityReportTerm
     */
    static public function getReportTermById($id)
    {
        /**
         * @var $admin EntityReportTerm
         */
        $ReportTerm = EntityReportTerm::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $ReportTerm;
    }

    public function getReportTermByCompanyId($company_id)
    {
        $ReportTerm = EntityReportTerm::findFirst(['conditions' => 'company_id = :company_id:', 'bind' => ['company_id' => $company_id]]);
        return $ReportTerm;
    }

    public function getLast()
    {
        return EntityReportTerm::query()
            ->orderBy('id asc')
            ->execute();
    }


    public function addNew($data)
    {
        $BillTerm = new EntityReportTerm();
        $BillTerm->setPayment($data["payment"]);
        $BillTerm->setCompanyId($data["company_id"]);
        $BillTerm->setCompanyName($data['company_name']);
        $BillTerm->setTerm($data["term"]);
        $BillTerm->setType($data["type"]);
        $BillTerm->setTime(time());
        if (!$BillTerm->save()) {
            throw new InvalidRepositoryException($BillTerm->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $ReportTerm = ReportTerm::getReportTermById($id);
        $ReportTerm->setPayment($data["payment"]);
        $ReportTerm->setTerm($data["term"]);
        $ReportTerm->setType($data["type"]);
        if (!$ReportTerm->save()) {
            throw new InvalidRepositoryException($ReportTerm->getMessages()[0]);
        }

        return true;
    }

    static public function deleteTerm($id)
    {
        $ReportTerm = ReportTerm::getReportTermById($id);
        if (!$ReportTerm) {
            throw new InvalidRepositoryException("未找到信息");
        }

        if (!$ReportTerm->delete()) {
            throw new InvalidRepositoryException("信息删除失败");
        }

        return true;
    }

}