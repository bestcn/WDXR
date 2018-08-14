<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 13:40
 * Type:省市区三级联动
 */
namespace Wdxr\Modules\Company\Controllers;

use Wdxr\Models\Repositories\Area;
use Wdxr\Models\Repositories\Citie;
use Wdxr\Models\Repositories\Province;
use Wdxr\Models\Repositories\Regions;

class SelectController
{

    //获取省份下级所有市区
    public function change_provinceAction()
    {
        $this->view->disable();
        $provinceid = $_GET['provinceid'];
        $citie = Regions::getSubRegions($provinceid);
        $option = '';
        foreach($citie as $k=>$v){
            $option .= "<option value='".$v['id']."'>".$v['name']."</option>";
        }
        return $this->response->setContent($option);
    }

    //获取市级以下所有县区
    public function change_citieAction()
    {
        $this->view->disable();
        $citieid = $_GET['citieid'];
        $all_area = Regions::getSubRegions($citieid);;
        $option = '';
        foreach($all_area as $k=>$v){
            $option .= "<option value='".$v['id']."'>".$v['name']."</option>";
        }
        return $this->response->setContent($option);
    }

    //获取区县级
    public function get_areaAction($id)
    {
        $area = Regions::getSubRegions($id);
        $area_array = array();
        foreach($area as $k=>$v){
            $area_array[$v['id']] = $v['name'];
        }
        return $area_array;
    }

    //获取市级
    public function get_citieAction($id)
    {
        $citie = Regions::getSubRegions($id);
        $citie_array = array();
        foreach($citie as $k=>$v){
            $citie_array[$v['id']] = $v['name'];
        }
        return $citie_array;
    }

    //获取省级
    public function get_provinceAction()
    {
        $province = Regions::getSubRegions(86);
        $province_array = array();
        foreach($province as $k=>$v){
            $province_array[$v['id']] = $v['name'];
        }
        return $province_array;
    }

    //修改时获取市级
    public function get_edit_citieAction($id)
    {
        $citie = Regions::getSubRegions($id);
        $citie_array =array();
        foreach($citie as $k=>$v){
            $citie_array[$v['id']] = $v['name'];
        }
        return $citie_array;
    }

    //修改时获取区县
    public function get_edit_areaAction($id)
    {
        $area = Regions::getSubRegions($id);
        $area_array = array();
        foreach($area as $k=>$v){
            $area_array[$v['id']] = $v['name'];
        }
        return $area_array;
    }


}