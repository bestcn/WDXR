<?php
namespace Wdxr\Models\Services;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Wdxr\Models\Repositories\Admin as RepoAdmin;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Repositories\CommissionList;
use Wdxr\Models\Repositories\Probation;
use Wdxr\Models\Repositories\Commission;
use Wdxr\Models\Repositories\Achievement;

class Admin extends Services
{

    /**
     * 修改密码
     * @param $id
     * @param $old_password
     * @param $new_password
     * @return bool
     * @throws InvalidServiceException
     */
    static public function changePassword($id, $old_password, $new_password)
    {
        $admin = RepoAdmin::getAdminById($id);
        $is_right = $admin->getDI()->get('security')->checkHash($old_password, $admin->getPassword());
        if($is_right === false) {
            throw new InvalidServiceException("旧密码错误");
        }
        $repo = new RepoAdmin();
        if($repo->changePassword($id, $new_password) !== true) {
            throw new InvalidServiceException("密码修改失败");
        }

        return true;
    }

    //添加andwhere 以控制分站管理员
    static public function getAdminListPagintor($parameters, $numberPage,$andwhere = null)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->andWhere("$andwhere")
            ->from('Wdxr\Models\Entities\Admins')
            ->orderBy('id');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }


    public function editAdminCommission($id, $data)
    {
        $device_id = UserAdmin::getDeviceId($id, UserAdmin::TYPE_ADMIN);
        $CommissionList = new CommissionList();
        $Commission_info = $CommissionList->getCommissionListByDeviceId($device_id);
        if ($Commission_info === false) {
            throw new InvalidServiceException('找不到当前业务员提成信息');
        }
        $Commission_data['status'] = $data['is_probation'];
        if ($data['is_probation'] == 1) {
            $probation = Probation::getProbationByBranchsId($data['branch_id']);
            if ($probation === false) {
                throw new InvalidServiceException("查询不到当前分公司试用期设置");
            }
            $Commission_data['ratio'] = $probation->getRatio();
            $CommissionList->edit($Commission_info->getId(), $Commission_data);
        } elseif ($data['is_probation'] == 0) {
            $money = Achievement::getMoneyByAdminId($id);
            if ($money === false) {
                $amount = 0;
            } else {
                $amount = $money->amount;
            }
            $Commission_ratio = Commission::getCommissionByAmount($data['branch_id'], $amount);
            if ($Commission_ratio === false) {
                $data['ratio'] = Commission::DEFULT_RATIO;
            } else {
                $data['ratio'] = $Commission_ratio->getRatio();
            }
            $Commission_data['ratio'] = $data['ratio'];
            $CommissionList->edit($Commission_info->getId(), $Commission_data);
        }
    }

}
