<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Areas as EntityArea;
use Wdxr\Models\Entities\Regions as EntityRegion;

class Area
{

    public function getLast()
    {
        return EntityArea::query()
            ->orderBy('id ASC')
            ->execute();
    }

    public function get_area($id)
    {
        return EntityArea::find(['conditions' => 'cityid = :cityid:', 'bind' => ['cityid' => $id]]);
    }

    static public function getArea($city_id)
    {
        return EntityRegion::find(['conditions' => 'depth = 3 and pid = :pid:', 'bind' => ['pid' => $city_id], 'columns' => 'id as areaid, name as area']);
    }

    static public function getAreaByid($id)
    {
        $Province = EntityArea::findFirst(['conditions' => 'areaid = :areaid:', 'bind' => ['areaid' => $id]]);
        return $Province->getArea();
    }

}