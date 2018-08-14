<?php
namespace Wdxr\Modules\Admin\Controllers;

use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Repositories\Repositories;

/**
 * 数据统计
 * Class StatController
 * @package Wdxr\Modules\Admin\Controllers
 */
class StatController extends ControllerBase
{

    public function company_list_by_adminAction()
    {
        $list = [];
        $admins = Repositories::getRepository('Admin')->getAdminsCompanyList();
        foreach ($admins as $key => $admin) {
            $list[$key]['name'] = $admin->name;
            $list[$key]['admin_id'] = $admin->admin_id;
            $list[$key]['device_id'] = $admin->device_id;
            $list[$key]['status'] = $admin->status;

            $list[$key]['achievement'] = Repositories::getRepository('Achievement')->getAdminAmount($admin->admin_id);
            $recommendeds = Repositories::getRepository('CompanyRecommend')->getRecommendedCompanyByAdmin($admin->admin_id);
            $list[$key]['d_count'] = $recommendeds->count();

            $all_recommendeds = Repositories::getRepository('Company')->getAdminCompany($admin->admin_id);
            $list[$key]['all_count'] = $all_recommendeds->count();
        }


        $this->view->setVar('admins', $list);
    }


    public function view_admin_companyAction($admin_id)
    {
        $admin = Admin::getAdminById($admin_id);
        $this->view->setVar('admin', $admin);

        $direct_recommendeds = Repositories::getRepository('CompanyRecommend')->getRecommendedCompanyByAdmin($admin_id);
        $this->view->setVar('recommendeds', $direct_recommendeds);

        $recommends = Repositories::getRepository('CompanyRecommend')->getRecommendCompanyMind($admin_id);
        $this->view->setVar('recommends', \GuzzleHttp\json_encode($recommends));
    }



}