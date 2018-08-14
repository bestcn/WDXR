<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\CompanyRecommend as EntityCompanyRecommend;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Repositories\Company as RepoCompany;

class CompanyRecommend extends Repositories
{

    const STATUS_DISABLE = 0;
    const STATUS_ENABLE = 1;

    /**
     * 获取推荐的企业列表
     * @param $company_id
     * @return \Phalcon\Mvc\Model\Resultset|bool
     */
    public function getRecommends($company_id)
    {
        $builder = $this->modelsManager->createBuilder()
            ->from(['company_recommend' => 'Wdxr\Models\Entities\CompanyRecommend'])
            ->join('Wdxr\Models\Entities\CompanyService', "company_recommend.recommend_id = company_service.company_id", 'company_service')
            ->join('Wdxr\Models\Entities\Companys', "companys.id = company_recommend.recommend_id", 'companys')
            ->where('companys.status = ?0 and company_service.service_status = ?1 and company_recommend.recommender = ?2', [RepoCompany::STATUS_ENABLE, CompanyService::SERVICE_ENABLE, $company_id])
            ->columns(['companys.name', 'UNIX_TIMESTAMP(company_recommend.time) as recommend_time', "FROM_UNIXTIME(company_service.start_time, '%Y-%m-%d')", "FROM_UNIXTIME(company_service.end_time, '%Y-%m-%d')"])
            ->orderBy('company_recommend.time desc');

        $recommends = $builder->getQuery()->execute();
        return $recommends;
    }

    /**
     * 当期内的推荐企业是否满足免费续签条件
     * @param $company_id
     * @return mixed
     */
    public function getCurrentRecommend($company_id)
    {
        $service = CompanyService::isInService($company_id);
        //如果找不到，则表示该企业已过期或尚未入驻
        if($service === false) {
            /**
             * @var $company_service \Wdxr\Models\Repositories\CompanyService
             */
            $company_service = Repositories::getRepository('CompanyService');
            //如果找不到则表示该企业尚未入驻
            $service = $company_service->getLastCompanyService($company_id);
            //对于有未生效的订阅服务时，默认认为已经签订了续费协议
            if($service === false || $service->getServiceStatus() === CompanyService::SERVICE_UN_ACTIVE) {
                return false;
            }
        }
        $recommends = $this->modelsManager->createBuilder()
            ->from(['company_recommend' => 'Wdxr\Models\Entities\CompanyRecommend'])
            ->join('Wdxr\Models\Entities\CompanyService', "company_recommend.recommend_id = company_service.company_id", 'company_service')
            ->join('Wdxr\Models\Entities\Companys', "companys.id = company_recommend.recommend_id", 'companys')
            ->where('companys.status = ?0 and company_service.service_status = ?1 and company_recommend.recommender = ?2', [RepoCompany::STATUS_ENABLE, CompanyService::SERVICE_ENABLE, $company_id])
            ->andWhere('UNIX_TIMESTAMP(company_recommend.time) between ?0 and ?1', [$service->getStartTime(), $service->getEndTime()])
            ->getQuery()->execute();

        return $recommends;
    }

    /**
     * @param $recommender string 推荐人
     * @param $recommend_id string 被推荐的人
     * @param $device_id
     * @return bool
     * @throws InvalidRepositoryException
     */
    public static function addNew($recommender, $recommend_id, $device_id)
    {
        $recommend = new EntityCompanyRecommend();
        $recommend->setRecommender($recommender);
        $recommend->setRecommendId($recommend_id);
        $recommend->setDeviceId($device_id);

        if (!$recommend->save()) {
            $msg = $recommend->getMessages()[0] ? : "推荐企业记录添加失败";
            throw new InvalidRepositoryException($msg);
        }
        return true;
    }

    public function getRecommendId($id)
    {
        $recommends =  EntityCompanyRecommend::find(['conditions' => 'recommender = :id:',
            'bind' => ['id' => $id],
            'order' => 'time desc'
        ]);

        $list = [];
        foreach ($recommends as $key => $recommend) {
            if($recommend->companys && $recommend->companys->getStatus() == RepoCompany::STATUS_ENABLE) {
                $list[] = [
                    'id' => $recommend->companys->getId(),
                    'name' => $recommend->companys->getName(),
                    'type' => $recommend->companys->users->getIsPartner() ? '事业合伙人' : '普惠客户',
                    'type_id' => $recommend->companys->users->getIsPartner() ? 1 : 2,
                    'recommend_name' => $recommend->companys->company_info->getLegalName()
                ];
            }
        }
        return $list;
    }

    static public function getAllRecommendCompany()
    {
        $recommend_company = EntityCompanyRecommend::query()
            ->conditions("recommend_id is not null")
            ->execute();

        return $recommend_company;
    }

    /**
     * 获取一个企业推荐的企业及所有下属企业，用层级方式表示的企业关系
     * @param $company_id
     * @param bool $is_mind
     * @return array
     */
    public function getRecommendedCompany($company_id, $is_mind = false)
    {
        /**
         * @var $recommends \Wdxr\Models\Entities\CompanyRecommend[]
         */
        $recommends = $this->modelsManager->createBuilder()
            ->from(['company_recommend' => 'Wdxr\Models\Entities\CompanyRecommend'])
            ->where('company_recommend.recommender = :id:', ['id' => $company_id])
            ->orderBy('company_recommend.time desc')
            ->getQuery()
            ->execute();

        $list = [];
        if ($is_mind) {
            foreach ($recommends as $key => $recommend) {
                $company = Company::getCompanyById($recommend->getRecommendId());
                $service = CompanyService::getCompanyService($recommend->getRecommendId());
                $status = $service === false ? '未入驻' : ($service->getServiceStatus() == 1 ? '已入驻' : '未入驻');
                $type = $service === false ? '' : ($service->getType() == 1 ? '[事业合伙人]' : '[普惠客户]');
                $item['id'] =  "{$company->getId()}";
                $item['topic'] =  $type.$company->getName()."(".$status.")";
                $item['description'] = $company->company_info->getLegalName();
                $list[$key] = $item;
                $children = $this->getRecommendedCompany($recommend->getRecommendId(), true);
                if (empty($children) === false) {
                    $list[$key]['children'] = $children;
                }
            }
        } else {
            foreach ($recommends as $key => $recommend) {
                $company = Company::getCompanyById($recommend->getRecommendId());
                $service = CompanyService::getCompanyService($recommend->getRecommendId());
                $list[$key]['company_id'] = $company->getId();
                $list[$key]['name'] = $company->getName();
                $list[$key]['legal_name'] = $company->company_info->getLegalName();
                $list[$key]['time'] = $service === false ? '无' : $service->getStartTime();
                $list[$key]['status'] = $service === false ? 0 : $service->getServiceStatus();
                $list[$key]['status_name'] = $service === false ? '未生效' : CompanyService::getStatusName($service->getServiceStatus());
                $list[$key]['type'] = $service === false ? '无' : ($service->getType() == 1 ? '事业合伙人' : '普惠客户');
                $list[$key]['next'] = $this->getRecommendedCompany($recommend->getRecommendId());
            }
            usort($list, function ($a, $b) {
                return $a['time'] > $b['time'];
            });
        }

        return $list;
    }

    /**
     * 思维导图形式展示客户关系
     * @param $admin_id
     * @return array
     */
    public function getRecommendCompanyMind($admin_id)
    {
        $admin = Admin::getAdminById($admin_id);
        $mind = ['id' => $admin->getId(), 'topic' => $admin->getName(), 'children' => []];
        $direct_companies = $this->getRecommendedCompanyByAdmin($admin->getId());
        foreach ($direct_companies as $key => $direct_company) {
            $mind['children'][$key] = ['id' => $direct_company->getId(), 'topic' => $direct_company->getName(), 'children' => []];
            $company = $this->getRecommendedCompany($direct_company->getId(), true);
            if(empty($company) === false) {
                $mind['children'][$key]['children'] = $mind['children'][$key]['children'] + $this->getRecommendedCompany($direct_company->getId(), true);
            }
        }

        return $mind;
    }

    /**
     * 获取业务员的直推客户
     * @param $admin_id
     * @return \Wdxr\Models\Entities\Companys[]
     */
    public function getRecommendedCompanyByAdmin($admin_id)
    {
        /**
         * @var $recommends \Wdxr\Models\Entities\Companys[]
         */
        $recommends = $this->modelsManager->createBuilder()
            ->from(['company' => 'Wdxr\Models\Entities\Companys'])
            ->join('Wdxr\Models\Entities\CompanyService', "company.id = company_service.company_id", 'company_service')
            ->where("company_service.service_status = :status: and company.admin_id = :admin_id: and (company.recommend_id IS NULL or company.recommend_id = '')", ['admin_id' => $admin_id, 'status' => CompanyService::SERVICE_ENABLE])
            ->orderBy('time desc')
            ->getQuery()
            ->execute();

        return $recommends;
    }

    /**
     * 获取可能成为推荐企业的企业列表
     * @param $company_id
     * @return mixed
     */
    public function getProbablyRecommend($company_id)
    {
        $company = RepoCompany::getCompanyById($company_id);

        $recommenders = $this->modelsManager->createBuilder()
            ->from(['company' => 'Wdxr\Models\Entities\Companys'])
            ->join('Wdxr\Models\Entities\CompanyService', "company.id = company_service.company_id", 'company_service')
            ->where("company_service.service_status = :status: and company.admin_id = :admin_id: and UNIX_TIMESTAMP(company.time) < UNIX_TIMESTAMP(:time:)", ['admin_id' => $company->getAdminId(), 'status' => CompanyService::SERVICE_ENABLE, 'time' => $company->getTime()])
            ->columns(['company.id', 'company.name'])
            ->orderBy('company.time asc')
            ->getQuery()
            ->execute();

        return $recommenders;
    }

    public function stopCompanyRecommend($company_id)
    {
        $company_recommends = EntityCompanyRecommend::find([
            'conditions' => 'recommender = ?0 or recommend_id = ?1',
            'bind' => [$company_id, $company_id],
        ]);

        return $company_recommends->update([
            'status' => self::STATUS_DISABLE
        ]);
    }

    public function getValidRecommend($recommender_id)
    {
        $recommends = $this->modelsManager->createBuilder()
            ->from(['company_recommend' => 'Wdxr\Models\Entities\CompanyRecommend'])
            ->join('Wdxr\Models\Entities\Companys', 'company.id = company_recommend.recommend_id', 'company')
            ->join('Wdxr\Models\Entities\CompanyService', 'company_service.company_id = company.id', 'company_service')
            ->where(
                'company_recommend.recommender = ?0 and company_recommend.status = ?1',
                [$recommender_id, CompanyReport::STATUS_ENABLE]
            )->andWhere('company.status = ?0', [Company::STATUS_ENABLE])
            ->andWhere('company_service.service_status = ?1', [CompanyService::SERVICE_ENABLE])
            ->distinct('company.id')
            ->columns([
                'company.id', 'company.name', 'company_service.id',
                'company_service.start_time', 'company_service.end_time'
            ])
            ->orderBy('company_service.start_time asc')
            ->getQuery()
            ->execute();

        return $recommends;
    }

    public function getRecommend($recommender, $recommend_id)
    {
        $company_recommends = EntityCompanyRecommend::findFirst([
            'conditions' => 'recommender = ?0 and recommend_id = ?1',
            'bind' => [$recommender, $recommend_id],
        ]);

        return $company_recommends;
    }

    public function changeRecommend($recommender, $old_recommender, $recommend_id)
    {
        $company = Company::getCompanyById($recommend_id);
        $recommend = $this->getRecommend($old_recommender, $recommend_id);

        //匹配旧推荐人
        if ($company->getRecommendId() || $recommend) {
            if ($company->getRecommendId() != $old_recommender || $recommend === false) {
                throw new InvalidRepositoryException("修改失败，旧的推荐关系不匹配");
            }
        }

        $this->db->begin();
        $company->setRecommendId($recommender);
        if ($company->save() === false) {
            $this->db->rollback();
            throw new InvalidRepositoryException("企业推荐关系修改失败");
        }
        //删除旧推荐关系
        if ($recommend) {
            if ($recommend->delete() === false) {
                $this->db->rollback();
                throw new InvalidRepositoryException("旧的推荐关系删除失败");
            }
        }
        try {
            CompanyRecommend::addNew($recommender, $recommend_id, $company->getDeviceId());
        } catch (InvalidRepositoryException $exception) {
            $this->db->rollback();
            throw new InvalidRepositoryException("新的推荐关系添加失败");
        }
        $this->db->commit();
        return true;
    }

}