<?php
namespace Wdxr\Modules\Admin\Controllers;

use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\CompanyService;
use Wdxr\Models\Repositories\Repositories;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Repositories\VerifyMessages;
use Wdxr\Models\Entities\VerifyMessages as EntityVerifyMessages;
use Wdxr\Models\Repositories\CompanyPayment;
use Wdxr\Models\Services\CompanyVerify as SerCompanyVerify;
use Wdxr\Models\Entities\CompanyRecommend as EntityCompanyRecommend;
use Wdxr\Models\Services\Services;
use Wdxr\Time;

/**
 * Class IndexController
 * @package Wdxr\Modules\Admin\Controllers
 *
 * @property \Wdxr\Auth\Auth $auth
 */
class IndexController extends ControllerBase
{

    public function indexAction()
    {
        /**
         * @var $company_payment CompanyPayment
         */
        $company_payment = Repositories::getRepository('CompanyPayment');
        //网站总收入
        $amount = $company_payment->getSumAmount();
        $this->view->setVar('amount', $amount?:'0.00');
        //本月收入
        $month = $company_payment->getSumAmountByMonth();
        //上月收入
        $last_month = $company_payment->getSumAmountByMonth(Time::getLastMonth(), date('d'));
        $income_rate = $last_month == 0 ? '无' : round(($month - $last_month) / $last_month, 2);
        $this->view->setVars([
            'month' => $month ? : '0.00',
            'income_rate' => $income_rate,
            'last_month' => $last_month
        ]);

        /**
         * @var $company \Wdxr\Models\Services\Company
         */
        $company = Services::getService('Company');
        //本月
        $partner_new_companies_count = $company->getMonthSumCompanies();
        $ordinary_new_companies_count = $company->getMonthSumCompanies(false);
        //上个月
        $partner_last_month_companies_count = $company->getMonthSumCompanies(true, Time::getLastMonth(), date('d'));
        $ordinary_last_month_companies_count = $company->getMonthSumCompanies(false, Time::getLastMonth(), date('d'));
        $new_count = $partner_new_companies_count + $ordinary_new_companies_count;
        $last_month_count = $partner_last_month_companies_count + $ordinary_last_month_companies_count;
        $company_rate = $last_month_count == 0 ? '无' : round(($new_count - $last_month_count)/$last_month_count, 2);
        $this->view->setVars([
            'partner_new_companies_count' => $partner_new_companies_count,
            'ordinary_new_companies_count' => $ordinary_new_companies_count,
            'company_rate' => $company_rate,
            'last_month_count' => $last_month_count
        ]);

        //总客户数量
        $partner_companies = $company->getPartnerCompanies(true);
        $this->view->setVar('partner_count', count($partner_companies));
        $ordinary_companies = $company->getPartnerCompanies(false);
        $this->view->setVar('ordinary_count', count($ordinary_companies));

        /**
         * @var $company_service CompanyService
         */
        $company_service = Repositories::getRepository('CompanyService');

        $start_time = strtotime(date('Y-m-d', strtotime("-31 day")));
        $partner_month_companies = $company_service->getCompanyCount($start_time, time(), true);
        $ordinary_month_companies = $company_service->getCompanyCount($start_time, time(), false);

        $start_time = strtotime(date('Y-m-d', strtotime("-62 day")));
        $end_time = strtotime(date('Y-m-d', strtotime("-31 day")));
        $last_partner_month_companies = $company_service->getCompanyCount($start_time, $end_time, true);
        $last_ordinary_month_companies = $company_service->getCompanyCount($start_time, $end_time, false);

        $count = [];
        for ($i = 31; $i >= 0; $i--) {
            $start_time = strtotime(date('Y-m-d', strtotime("-$i day")));
            $end_time = $start_time + 86400;
            $options = ['time' => date('Y-m-d', $start_time).' - '.date('Y-m-d', $end_time)];

            $builder = $company_service->getServiceCompany($options);
            $count[$i]['value'] = count($builder->getQuery()->execute());
            $count[$i]['year'] = date('Y', $start_time);
            $count[$i]['month'] = date('m', $start_time);
            $count[$i]['day'] = date('d', $start_time);
        }
        $month_count = $partner_month_companies + $ordinary_month_companies;
        $last_month_count = $last_partner_month_companies + $last_ordinary_month_companies;
        $this->view->setVars([
            'count' => $count,
            'month_count' => $month_count,
            'partner_month_companies' => $partner_month_companies,
            'partner_percent' => round($partner_month_companies / $month_count, 2) * 100,
            'ordinary_month_companies' => $ordinary_month_companies,
            'ordinary_percent' => round($ordinary_month_companies / $month_count, 2) * 100,
            'company_percent' => round($month_count / ($last_month_count + $month_count), 2) * 100,
            'last_company_count' => $last_month_count
        ]);
    }

    //获取未读消息
    public function newsAction()
    {
        $this->view->disable();
        if($this->request->isAjax()){
            $data = [];
            /**
             * @var $message EntityVerifyMessages[]
             */
            $message = VerifyMessages::getUnread5Verify();
            foreach ($message as $key => $val){
                $data['data'][$key]['title'] = $val->getTitle();
                $data['data'][$key]['id'] = $val->getId();
            }
            $data['count']=count(VerifyMessages::getUnreadVerify()->toArray());
            $data["status"] = 1;
            return $this->response->setJsonContent($data);
        }
    }

    //获取各个未审核数量
    public function pendingAction()
    {
        $this->view->disable();
        if ($this->request->isAjax()) {
            $data=SerCompanyVerify::getUnCompanyVerifyNum();
            $response = $this->response;
            $data["status"]=1;
            return $response->setJsonContent($data);
        }
        return 0;
    }

}
