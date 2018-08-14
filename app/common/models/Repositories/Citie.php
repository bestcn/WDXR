<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Cities as EntityCity;
use Wdxr\Models\Entities\Regions as EntityRegion;

class Citie
{


    public function getLast()
    {
        return EntityCity::query()
            ->orderBy('id ASC')
            ->execute();
    }

    public function get_citie($id)
    {
        return EntityCity::find(['conditions' => 'provinceid = :provinceid:', 'bind' => ['provinceid' => $id]]);
    }

    static public function getCities($pid)
    {
        return EntityRegion::find(['conditions' => 'depth = 2 and pid = :pid:', 'bind' => ['pid' => $pid], 'columns' => 'id as cityid, name as city']);
    }

    static public function getCitiesByid($id)
    {
        $Province = EntityCity::findFirst(['conditions' => 'cityid = :cityid:', 'bind' => ['cityid' => $id]]);
        return $Province->getCity();
    }

}