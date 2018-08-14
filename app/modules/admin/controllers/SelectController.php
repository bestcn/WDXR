<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 13:40
 * Type:省市区三级联动
 */
namespace Wdxr\Modules\Admin\Controllers;

use Wdxr\Models\Repositories\Area;
use Wdxr\Models\Repositories\Citie;
use Wdxr\Models\Repositories\Province;
use Wdxr\Models\Repositories\Regions;
use Wdxr\Models\Services\Services;

class SelectController extends ControllerBase
{

    public function getSubRegionsAction()
    {
        $this->view->disable();
        $pid = $this->request->getPost("pid");
        $regions = Regions::getSubRegions($pid);

        return $this->response->setJsonContent($regions);
    }

    //获取省份下级所有市区
    public function change_provinceAction()
    {
        $this->view->disable();
        $provinceid = $_GET['provinceid'];
        $all_citie = Regions::getSubRegions($provinceid);
        $option = '';
        foreach($all_citie as $k=>$v) {
            $option .= "<option value='".$v['id']."'>".$v['name']."</option>";
        }
        return $this->response->setContent($option);
    }

    //获取市级以下所有县区
    public function change_citieAction()
    {
        $this->view->disable();
        $citieid = $_GET['citieid'];
        $all_area = Regions::getSubRegions($citieid);
        $option = '';
        foreach($all_area as $k=>$v){
            $option .= "<option value='".$v['id']."'>".$v['name']."</option>";
        }
        return $this->response->setContent($option);
    }

    //获取区县级
    public function get_areaAction($id=null)
    {
        $area = Regions::getSubRegions($id);
        $area_array = array();
        foreach($area as $k=>$v){
            $area_array[$v['id']] = $v['name'];
        }
        return $area_array;
    }

    //获取市级
    public function get_citieAction($id=null)
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
        $citie_array = array();
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

    public function categoryAction()
    {
        $this->view->disable();
        $service = Services::Hprose('Category');
        $all_sub = $service->getSub($_GET['top_category']);
        $option = '';
        foreach($all_sub as $k=>$v){
            $option .= "<option value='".$v['code']."'>".$v['name']."</option>";
        }
        return $this->response->setContent($option);
    }

    public function getSubCategoryAction()
    {
        $this->view->disable();
        $service = Services::Hprose('Category');
        $code = $this->request->getPost("code");
        $all_sub = $service->getSub($code);
        $option = '';
        foreach($all_sub as $k=>$v){
            $option .= "<option value='".$v['code']."'>".$v['name']."</option>";
        }
        return $this->response->setContent($option);
    }


}