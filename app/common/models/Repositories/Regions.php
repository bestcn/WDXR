<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Regions as EntityRegions;

class Regions extends Repositories
{

    static public function getRegionName($region_id)
    {
        return EntityRegions::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $region_id], 'columns' => 'name']);
    }

    /**
     * 获取完整的省市县三级地址
     * @param $province_id
     * @param $city_id
     * @param $district_id
     * @param $address
     * @return string
     */
    static public function getAddress($province_id, $city_id, $district_id, $address)
    {
        $province = self::getRegionName($province_id);
        $city = self::getRegionName($city_id);
        $district = self::getRegionName($district_id);

        return self::inAddress($address, $province).self::inAddress($address, $city).self::inAddress($address, $district).$address;
    }

    /**
     * 判断字符串地址中是否存在省市县
     * @param $address
     * @param $region
     * @return string
     */
    static public function inAddress($address, $region)
    {
        if(is_object($region) && $region->name){
            return mb_strpos($address, $region->name) === false ? $region->name : '';
        }
        return '';
//        return (is_object($region) && mb_strpos($address, $region->name) === false) ? $region->name : '';
    }

    static public function getRegions()
    {
        $list = [];
        $regions = EntityRegions::find();
        foreach ($regions as $region) {
            if($region->getDepth() == 1) {
                $list[$region->getId()]['areaId'] = $region->getId();
                $list[$region->getId()]['areaName'] = $region->getName();
            } else if($region->getDepth() == 2) {
                $list[$region->getPid()]['cities'][$region->getId()]['areaId'] = $region->getId();
                $list[$region->getPid()]['cities'][$region->getId()]['areaName'] = $region->getName();
            } else if($region->getDepth() == 3){
                $top_id = EntityRegions::findFirst(['conditions' =>  "id = :pid:", 'bind' => ['pid' => $region->getPid()]]);
                $list[$top_id->getPid()]['cities'][$region->getPid()]['counties'][$region->getId()]['areaId'] = $region->getId();
                $list[$top_id->getPid()]['cities'][$region->getPid()]['counties'][$region->getId()]['areaName'] = $region->getName();
            }
        }

        foreach ($list as $key=>$val) {
            foreach($val['cities'] as $k=>$v){
                $val['cities'][$k]['counties'] = array_values($v['counties']);
            }
            $list[$key]['cities'] = array_values($val['cities']);
        }

        return $list;
    }

    /**
     * 获取下一级地区信息
     * @param $pid
     * @return array|bool
     */
    static public function getSubRegions($pid)
    {
        $regions = EntityRegions::find(["pid = :pid:", 'bind' => ['pid' => $pid]]);
        if($regions) {
            return $regions->toArray();
        }
        return false;
    }

}