<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/10/13
 * Time: 13:59
 */
namespace Wdxr\Models\Repositories;

use Lcobucci\JWT\JWT;
use Wdxr\Models\Entities\Follow as EntityAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class Follow
{

    /**
     * @param $id
     * @return EntityAdmin
     */
    static public function getFollowById($id)
    {
        /**
         * @var $admin EntityAdmin
         */
        $Follow = EntityAdmin::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $Follow;
    }

    /*
     * 根据ID查询
     */
    static public function getFollowByDeviceId($device_id)
    {
        $Follow = EntityAdmin::findFirst(['conditions' => 'device_id = :device_id:', 'bind' => ['device_id' => $device_id]]);
        return $Follow;
    }

    static public function checkFollow($company_id)
    {
        $device_id = JWT::getUid();
        $follow_data = self::getFollowByDeviceId($device_id);
        if($follow_data) {
            $data = explode(',', $follow_data->getFollow());
            if(in_array($company_id,$data)){
                return true;
            }
        }
        return false;
    }

    public function getLast()
    {
        return EntityAdmin::query()
            ->orderBy('id asc')
            ->execute();
    }

    public function addNew($data)
    {
        $follow = new EntityAdmin();
        $follow->setDeviceId($data["device_id"]);
        $follow->setFollow($data["company_id"]);
        if (!$follow->save()) {
            throw new InvalidRepositoryException($follow->getMessages()[0]);
        }
        return true;
    }

    //关注
    static public function follow($company_id)
    {
        $device_id = JWT::getUid();
        $follow_data = self::getFollowByDeviceId($device_id);
        if($follow_data) {
            if($follow_data->getUnfollow() != null){
                $undata = explode(',', $follow_data->getUnfollow());
                $tempArr = array_flip($undata);
                unset($tempArr[$company_id]);
                $follow_data->setUnfollow(implode(array_flip($tempArr),','));
            }
            $data = explode(',', $follow_data->getFollow());
            if(!in_array($company_id,$data)){
                array_push($data, $company_id);
                $data = implode($data, ',');
                $follow_data->setFollow($data);
                if(!$follow_data->save()){
                    throw new InvalidRepositoryException("关注失败");
                }
            }
        }else{
            $data['device_id'] = $device_id;
            $data['company_id'] = $company_id;
            (new Follow())->addNew($data);
        }
    }

    //取消关注
    static public function unfollow($company_id)
    {
        $device_id = JWT::getUid();
        $follow_data = self::getFollowByDeviceId($device_id);
        if($follow_data) {
            if($follow_data->getFollow() != null){
                $data = explode(',', $follow_data->getFollow());
                $tempArr = array_flip($data);
                unset($tempArr[$company_id]);
                $follow_data->setFollow(implode(array_flip($tempArr),','));
            }
            $data = explode(',', $follow_data->getUnfollow());
            if(!in_array($company_id,$data)){
                array_push($data, $company_id);
                $data = implode($data, ',');
                $follow_data->setUnfollow($data);
                if(!$follow_data->save()){
                    throw new InvalidRepositoryException("取消关注失败");
                }
            }
        }else{
            $follow = new \Wdxr\Models\Entities\Follow();
            $follow->setDeviceId($device_id);
            $follow->setUnfollow($company_id);
            if(!$follow_data->save()){
                throw new InvalidRepositoryException("取消关注失败");
            }
        }
    }

    //关注列表
    static public function followList()
    {
        $device_id = JWT::getUid();
        $all_data = self::getFollowByDeviceId($device_id);
        if($all_data) {
            $follow_data = $all_data->getFollow() ? explode(',',$all_data->getFollow()) : [];
            $data = array();
            foreach($follow_data as $key=>$val){
                $company_data = (new Company())->Byid($val);
                if($company_data){
                    $company_ionfo_data = (new CompanyInfo())->getCompanyInfo($company_data->getInfoId());
                    $data[$key]['name'] = $company_data->getName();
                    $data[$key]['device'] = $company_data->getDeviceId() ? UserAdmin::getNameByDeviceId($company_data->getDeviceId()) : '待办理';
                    $data[$key]['licence_num'] = $company_ionfo_data->getLicenceNum();
                }
            }
            return $data;
        }
        return [];
    }

}