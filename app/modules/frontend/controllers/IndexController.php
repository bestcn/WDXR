<?php
namespace Wdxr\Modules\Frontend\Controllers;

use Wdxr\Models\Repositories\Repositories;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
    }

    public function aAction()
    {
        $this->view->disable();

        $list = [
            '赵军', '曹秋东', '李宝全', '管宏双', '张建俊', '赵华雅', '张春杰', '徐秀红', '吕晨阳', '谷静云', '林彦玲',
            '贾翠红', '曹玉芹', '白金路', '郭欣', '赵鹏飞', '尹国忠', '孙中华', '杨月敏', '曹利花', '李卫娟', '王艳丽',
            '翟彦双', '刘玲', '赵茹', '王利花', '孙德', '李云强', '郭美云', '徐素芹', '李莲莲', '荣志刚', '吕银超', '刘丽娟',
            '卢哲辉', '蔡妍妍', '李翠段', '赵素花', '王君兰', '周建虎', '王滋雨', '杨广彬', '刘玉婷', '乔四妮', '王会保', '卢红梅',
            '白严素', '祁美艳', '王志红', '佟海鹰', '何艳静', '马书芬', '何永法', '曹红卫', '陈玉改', '王立凯', '胡腾飞', '范玉华',
            '李杰飞', '门会霞', '谢小丽', '朱庆进', '肖艳芬', '杜香平', '扈伟涛', '杨虎', '王悦', '王雪莲', '杨立国', '周元米', '张宪会'
            ];
        $data = [];
        foreach ($list as $item) {
            $r = $this->getImage($item);
            array_push($data, $r);
        }

        \Wdxr\Models\Services\Excel::create()->title('客户店铺门头照片')->header(['店铺名称', '联系人', '联系方式', '门头URL'])
            ->value($data)->sheetTitle('客户店铺门头照片')->output('门头照片');
    }

    public function getImage($name)
    {
        $sql = "select `true_name`, `mobile`, `account_name`, `logo` from `shop_seller` WHERE `account_name` = '{$name}'";
        $query = $this->db->query($sql);

        $result = $query->fetch();


        if($result) {
            if($result['logo']) {
                $url = 'https://thumb.image.guanjia16.cn/'.$result['logo'];
                return [$result['true_name'], $result['account_name'], $result['mobile'], $url];
            } else {
                return [$result['true_name'], $result['account_name'], $result['mobile'], '无'];
            }
        }
        return ['', '', '', ''];
    }



}

