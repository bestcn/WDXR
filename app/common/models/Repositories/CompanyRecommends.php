<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\CompanyRecommend as EntityCompanyRecommends;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class CompanyRecommends extends Repositories
{

    /**
     * @param $id
     * @return EntityCompanyRecommends
     */
    static public function getCompanyRecommendsById($id)
    {
        /**
         * @var $admin EntityCompanyRecommends
         */
        $company = EntityCompanyRecommends::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $company;
    }

    //获取企业的上级企业
    public function getCompanyRecommendsByRecommender($recommender)
    {
        $company = EntityCompanyRecommends::findFirst(['conditions' => 'recommend_id = :recommend_id:', 'bind' => ['recommend_id' => $recommender]]);
        return $company;
    }
    //删除企业的推荐关系
    public function getCompanyRecommendsByRecommenderToDelete($recommender)
    {
        $company = EntityCompanyRecommends::findFirst([
            'conditions' => 'recommend_id = :recommend_id:', 'bind' => ['recommend_id' => $recommender]
        ]);
        if($company){
            $company->delete();
        }
    }

    /**
     * 删除推荐关系
     * @param $recommender
     * @param $recommend_id
     * @return bool
     */
    public function deleteCompanyRecommend($recommender, $recommend_id)
    {
        $recommend = EntityCompanyRecommends::findFirst([
            'conditions' => 'recommend_id = ?0 and recommender = ?1',
            'bind' => [$recommend_id, $recommender]
        ]);

        if($recommend) {
            return $recommend->delete();
        }
        return false;
    }

    //获取企业的下级企业
    public function getCompanyRecommendsByrecommend_id($recommend_id)
    {
        $company = EntityCompanyRecommends::find(['conditions' => 'recommender = :recommender:', 'bind' => ['recommender' => $recommend_id]]);
        if(!$company){
            throw new InvalidRepositoryException('没有下级企业');
        }else{
            return $company;
        }
    }

    //获取企业集合的推荐企业
    public function getCompanyRecommendsBystring($str)
    {
        return EntityCompanyRecommends::query()
            ->where($str)
            ->orderBy('time desc')
            ->execute();
    }


    public function getLast()
    {
        return EntityCompanyRecommends::query()
            ->orderBy('time asc')
            ->execute();
    }


    public function addNew($recommender, $recommend_id, $device_id)
    {
        $recommend = new EntityCompanyRecommends();
        $recommend->setRecommender($recommender);
        $recommend->setRecommendId($recommend_id);
        $recommend->setDeviceId($device_id);
        if ($recommend->save() === false) {
            $msg = isset($recommend->getMessages()[0]) ? $recommend->getMessages()[0] : "推荐企业保存失败";
            throw new InvalidRepositoryException($msg);
        }
        return $recommend->getWriteConnection()->lastInsertId($recommend->getSource());
    }

    public function edit($id, $data)
    {
        $recommend = CompanyRecommends::getCompanyRecommendsById($id);
        $recommend->setRecommendId($data["recommend_id"]);//被推荐企业ID
        $recommend->setRecommender($data["recommender"]);//推荐企业ID
        $recommend->setDeviceId($data["device_id"]);
        if (!$recommend->save()) {
            $message = isset($recommend->getMessages()[0]) ? $recommend->getMessages()[0] : "推荐企业保存失败";
            throw new InvalidRepositoryException($message);
        }

        return true;
    }


}