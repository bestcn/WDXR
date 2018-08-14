<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 13:40
 */
namespace Wdxr\Modules\Admin\Controllers;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\CompanyInfo;
use Wdxr\Models\Repositories\CompanyRecommends;
use Wdxr\Models\Repositories\Finance;
use Wdxr\Models\Repositories\Level;
use Wdxr\Models\Repositories\Recommend;
use Wdxr\Models\Repositories\Manage;

class MakeController extends ControllerBase
{

    public function makeAction()
    {
        //获取已经审核成功的企业基本信息
        $companys = Company::getTrueCompany();
        $info = new CompanyInfo();
        $finance = new Finance();

        //查询企业其他信息
        foreach($companys as $key => $val){

            //账户信息
            try{
                $data = array();
                $data['money'] = \Wdxr\Models\Services\Company::getCompanyBenefits($val);
                $infos = $info->getCompanyInfoById($val->getInfoId());
                $data['makecoll'] = $infos->getBankcard();
                $data['company_id'] = $val->getName();
                $data['remark'] = '每日报销';
                $data['time'] = time();
                //判断每个企业的报销数据是否已经生成
                $start_time = strtotime(date('Y-m-d',time()));
                $end_time = strtotime(date('Y-m-d',time()))+86400;
                $check = $finance->checkOnly($start_time,$end_time,$val->getName());
                if($check->toArray() == []){
                    //生成数据
                    $finance->addNew($data);
                }
            }
            catch(InvalidServiceException $Exception){
                $this->logger()->error($Exception->getMessage().'--'.$val->getId());
            }
            catch(InvalidRepositoryException $Exception){
                $this->logger()->error($Exception->getMessage().'--'.$val->getId());
            }

        }

    }

    public function recommendAction()
    {
        //获取所有推荐列表
        $CompanyRecommends = new CompanyRecommends();
        $CompanyRecommends = $CompanyRecommends->getLast()->toArray();

        $info = new CompanyInfo();
        $Recommend = new Recommend();
        //获取推荐企业信息列表
        if($CompanyRecommends){
            $new_data = array();
            //将企业推荐的多条记录合并
            foreach($CompanyRecommends as $key=>$val){
                try {
                    //判断下级企业是否通过审核
                    $company_data = (new Company())->getById($val['recommend_id']);
                    $amount = \Wdxr\Models\Services\Company::getCompanyBenefits($company_data);
                    @$new_data[$val['recommender']] += 1;
                    if ($new_data[$val['recommender']] > 12) {
                        $new_data[$val['recommender']] = 12;
                    }
                }
                catch(InvalidServiceException $e){
                    $this->logger()->error($e->getMessage());
                }
                catch(InvalidRepositoryException $e){
                    $this->logger()->error($e->getMessage());
                }
            }
            foreach($new_data as $key=>$val){
                try{
                    $company_data = (new Company())->getById($key);

                    try{
                        $amount = \Wdxr\Models\Services\Company::getCompanyBenefits($company_data);
                        //账户信息
                        $data = array();
                        $infos = $info->getCompanyInfoById($company_data->getInfoId());
                        $data['makecoll'] = $infos->getBankcard();
                        $data['company_id'] = $company_data->getName();
                        $data['money'] = 10*$val;
                        $data['remark'] = '推荐奖励';
                        $data['time'] = time();

                        //判断每个企业的报销数据是否已经生成
                        $start_time = strtotime(date('Y-m-d',time()));
                        $end_time = strtotime(date('Y-m-d',time()))+86400;
                        $check = $Recommend->checkOnly($start_time,$end_time,$company_data->getName());
                        if($check->toArray() == []){
                        //生成数据
                        $Recommend->addNew($data);
                        }
                    }
                    catch(InvalidServiceException $Exception){
                        $this->logger()->error($Exception->getMessage().'--'.$company_data->getId());
                    }
                    catch(InvalidRepositoryException $Exception){
                        $this->logger()->error($Exception->getMessage().'--'.$company_data->getId());
                    }
                }
                catch(InvalidRepositoryException $e){
                    $this->logger()->error($e->getMessage());
                }
            }
        }else{
            $this->logger()->error('没有推荐列表');
        }
    }

    public function manageAction()
    {
        //获取所有推荐列表
        $info = new CompanyInfo();
        $Recommend = new Manage();
        $CompanyRecommends = new CompanyRecommends();
        $CompanyRecommends_data = $CompanyRecommends->getLast()->toArray();
        if(!empty($CompanyRecommends_data)){
            $new_data = array();
            $reach = array();
            //获得推荐满12家的企业
            foreach ($CompanyRecommends_data as $key => $val) {
                //判断下级企业审核情况
                try {
                    //判断下级企业是否通过审核
                    $company_data = (new Company())->getById($val['recommend_id']);
                    $amount = \Wdxr\Models\Services\Company::getCompanyBenefits($company_data);

                    @$new_data[$val['recommender']] += 1;
                    if ($new_data[$val['recommender']] > +12) {
                        $reach[] = $val['recommender'];
                    }
                }
                catch(InvalidServiceException $e){
                    $this->logger()->error($e->getMessage());
                }
                catch(InvalidRepositoryException $e){
                    $this->logger()->error($e->getMessage());
                }

            }
            //判断企业是否有上级企业
            if($reach != []){
                    $data = array();
                foreach ($reach as $val){
                    //判断中间一级企业是否通过审核
                    try {
                        //判断下级企业是否通过审核
                        $company_data = (new Company())->getById($val);
                        $amount = \Wdxr\Models\Services\Company::getCompanyBenefits($company_data);
                        $data[] = $CompanyRecommends->getCompanyRecommendsByRecommender($val);
                    }
                    catch(InvalidServiceException $e){
                        $this->logger()->error($e->getMessage());
                    }
                    catch(InvalidRepositoryException $e){
                        $this->logger()->error($e->getMessage());
                    }
                }

                foreach($data as $key=>$val){
                    try{
                        $company_data = (new Company())->getById($val->getRecommender());

                        try{
                            $amount = \Wdxr\Models\Services\Company::getCompanyBenefits($company_data);
                            //账户信息
                            $data = array();
                            $infos = $info->getCompanyInfoById($company_data->getInfoId());
                            $data['makecoll'] = $infos->getBankcard();
                            $data['company_id'] = $company_data->getName();
                            $data['money'] = 3;
                            $data['remark'] = '管理费';
                            $data['time'] = time();
                            //判断每个企业的报销数据是否已经生成
                            $start_time = strtotime(date('Y-m-d',time()));
                            $end_time = strtotime(date('Y-m-d',time()))+86400;
                            $check = $Recommend->checkOnly($start_time,$end_time,$company_data->getName());
                            if($check->toArray() == []){
                                //生成数据
                                $Recommend->addNew($data);
                            }
                        }
                        catch(InvalidServiceException $Exception){
                            $this->logger()->error($Exception->getMessage().'--'.$company_data->getId());
                        }
                        catch(InvalidRepositoryException $Exception){
                            $this->logger()->error($Exception->getMessage().'--'.$company_data->getId());
                        }
                    }
                    catch(InvalidRepositoryException $e){
                        $this->logger()->error($e->getMessage());
                    }
                }

            }else{
                $this->logger()->error('没有管理费列表');
            }
        }else{
            $this->logger()->error('没有推荐列表');
        }


    }

}