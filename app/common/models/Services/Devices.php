<?php
namespace Wdxr\Models\Services;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Wdxr\Models\Repositories\Devices as RepDevices;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;

class Devices extends Services
{

    static public function getDevicesListPagintor($parameters, $numberPage)
    {
        $conditions = '1=1';
        if(!empty($parameters)) {
            $conditions = "name like '%".$parameters."%' ";
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions)
            ->from('Wdxr\Models\Entities\Devices')
            ->orderBy('id desc');
        $numberPage = empty($numberPage)?1:$numberPage;
        $paginator =  new PaginatorQueryBuilder(array(
            "builder" => $builder,
            "limit"=> 10,
            "page" => $numberPage,
        ));
        $Paginate=$paginator->getPaginate();
        $numberPage = ($numberPage-1)*10;
        $data=$builder->limit(10,$numberPage)->getQuery()->execute()->toArray();
        foreach ($data as $key=>$val){
            $data[$key]['user_name'] = UserAdmin::getNameByDeviceId($val['user_id']);
        }
        $Paginate->items = $data;
        return $Paginate;
    }


}