<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Provinces as EntityProvince;
use Wdxr\Models\Entities\Regions as EntityRegion;

class Province
{

    static public function getProvinceList()
    {
        return EntityRegion::find(['conditions' => 'depth = 1', 'columns' => 'id as provinceid, name as province']);
    }

    public function getLast()
    {
        return EntityProvince::query()
            ->orderBy('id ASC')
            ->execute();
    }

    static public function getProvinceByid($id)
    {
        $Province = EntityProvince::findFirst(['conditions' => 'provinceid = :provinceid:', 'bind' => ['provinceid' => $id]]);
        return $Province->getProvince();
    }


}