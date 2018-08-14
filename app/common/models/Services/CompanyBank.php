<?php
namespace Wdxr\Models\Services;

use Wdxr\Models\Entities\CompanyBank as EntityCompanyBank;
use Wdxr\Models\Repositories\CompanyBank as RepoCompanyBank;

class CompanyBank extends Services
{

    static public function paymentCompanyBank($company_id, $data)
    {
        RepoCompanyBank::saveCompanyBank($company_id, $data);
        if($data['bank_type'] == RepoCompanyBank::TYPE_PUBLIC) {
            $data['work_bank_type'] = RepoCompanyBank::TYPE_PRIVATE;
            $work_bank = array_filter($data, function($var) {
                return strpos($var, 'work_') === 0;
            }, ARRAY_FILTER_USE_KEY);
            $work_bank = array_map(function($var) {
                if(strpos($var, 'work_') === 0) {
                    return str_replace('work_', '', $var);
                }
            }, array_flip($work_bank));

            RepoCompanyBank::saveCompanyBank($company_id, array_flip($work_bank), RepoCompanyBank::CATEGORY_WORK);
        }

        return true;
    }

}