<?php
namespace Wdxr\Models\Services;

use Knp\Snappy\Pdf;
use Lcobucci\JWT\JWT;
use Wdxr\Models\Entities\Attachments as EntityAttachment;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Wdxr\Models\Entities\Companys;
use Wdxr\Models\Entities\Contracts;
use Wdxr\Models\Repositories\CompanyService;
use Wdxr\Models\Repositories\Repositories;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Repositories\Contract as RepoContract;
use Wdxr\Models\Repositories\CompanyPayment as RepoCompanyPayment;
use Wdxr\Models\Repositories\Level as RepoLevel;
use Wdxr\Models\Repositories\CompanyInfo as RepoCompanyInfo;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Models\Entities\Contracts as EntityContract;

class Contract extends Services
{

    /*static public function getContractOSSUrl($object)
    {
        return \OSS\Common::getOssClient()->signUrl(\OSS\Common::contract_bucket, $object);
    }*/

    static public function getImage($attachment_id)
    {
        $attachment = EntityAttachment::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $attachment_id], 'columns' => 'path']);
        if($attachment === false) {
            throw new InvalidServiceException("没有找到签名信息");
        }
        $path = $attachment->path;
        $info = getimagesize($path);
        $base64_image_content = "data:{$info['mime']};base64," . base64_encode(file_get_contents($path));

        return $base64_image_content;
    }

    public static function getContractListPagintor($parameters, $numberPage)
    {
        $conditions = '';
        $bind = [];
        if (!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from(['contract' => 'Wdxr\Models\Entities\Contracts'])
            ->join('Wdxr\Models\Entities\Companys', 'company.id = contract.company_id', 'company')
            ->columns([
                'contract.id', 'contract.contract_num', 'contract.contract_status', 'company.name',
                'contract.company_id', 'company.status', 'contract.time'
            ])->orderBy('contract.id desc');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 20,
            'page' => $numberPage
        ]);
    }

    /**
     * 人民币小写转大写
     *
     * @param string $number 数值
     * @param string $int_unit 币种单位，默认"元"，有的需求可能为"圆"
     * @param bool $is_round 是否对小数进行四舍五入
     * @param bool $is_extra_zero 是否对整数部分以0结尾，小数存在的数字附加0,比如1960.30，
     *             有的系统要求输出"壹仟玖佰陆拾元零叁角"，实际上"壹仟玖佰陆拾元叁角"也是对的
     * @return string
     */
    static public function num2rmb($number = 0, $int_unit = '元', $is_round = TRUE, $is_extra_zero = FALSE)
    {
        // 将数字切分成两段
        $parts = explode('.', $number, 2);
        $int = isset($parts[0]) ? strval($parts[0]) : '0';
        $dec = isset($parts[1]) ? strval($parts[1]) : '';

        // 如果小数点后多于2位，不四舍五入就直接截，否则就处理
        $dec_len = strlen($dec);
        if (isset($parts[1]) && $dec_len > 2)
        {
            $dec = $is_round
                ? substr(strrchr(strval(round(floatval("0.".$dec), 2)), '.'), 1)
                : substr($parts[1], 0, 2);
        }

        // 当number为0.001时，小数点后的金额为0元
        if(empty($int) && empty($dec))
        {
            return '零';
        }

        // 定义
        $chs = array('0','壹','贰','叁','肆','伍','陆','柒','捌','玖');
        $uni = array('','拾','佰','仟');
        $dec_uni = array('角', '分');
        $exp = array('', '万');
        $res = '';

        // 整数部分从右向左找
        for($i = strlen($int) - 1, $k = 0; $i >= 0; $k++)
        {
            $str = '';
            // 按照中文读写习惯，每4个字为一段进行转化，i一直在减
            for($j = 0; $j < 4 && $i >= 0; $j++, $i--)
            {
                $u = $int{$i} > 0 ? $uni[$j] : ''; // 非0的数字后面添加单位
                $str = $chs[$int{$i}] . $u . $str;
            }
            //echo $str."|".($k - 2)."<br>";
            $str = rtrim($str, '0');// 去掉末尾的0
            $str = preg_replace("/0+/", "零", $str); // 替换多个连续的0
            if(!isset($exp[$k]))
            {
                $exp[$k] = $exp[$k - 2] . '亿'; // 构建单位
            }
            $u2 = $str != '' ? $exp[$k] : '';
            $res = $str . $u2 . $res;
        }

        // 如果小数部分处理完之后是00，需要处理下
        $dec = rtrim($dec, '0');

        // 小数部分从左向右找
        if(!empty($dec))
        {
            $res .= $int_unit;

            // 是否要在整数部分以0结尾的数字后附加0，有的系统有这要求
            if ($is_extra_zero)
            {
                if (substr($int, -1) === '0')
                {
                    $res.= '零';
                }
            }

            for($i = 0, $cnt = strlen($dec); $i < $cnt; $i++)
            {
                $u = $dec{$i} > 0 ? $dec_uni[$i] : ''; // 非0的数字后面添加单位
                $res .= $chs[$dec{$i}] . $u;
            }
            $res = rtrim($res, '0');// 去掉末尾的0
            $res = preg_replace("/0+/", "零", $res); // 替换多个连续的0
        }
        else
        {
            $res .= $int_unit . '整';
        }
        return $res;
    }

    /**
     * @param $company_info
     * @param Companys $company
     * @param Contracts $contract
     * @param $sign_id
     * @return mixed
     */
    static public function generateApplyContractView($company_info, $company, $contract, $sign_id)
    {
        $company_id = $company->getId();
        $payment = RepoCompanyPayment::getPaymentByCompanyId($company_id);
        $level = RepoLevel::getLevelByCompanyId($company_id);
        $address = RepoCompanyInfo::getDetailAddress($company_info['province'], $company_info['city'], $company_info['district'], $company_info['address']).$company_info['address'];
//        $bank = RepoCompanyInfo::getBankDetailAddress($company_info['bank_province'], $company_info['bank_city']).$company_info['bank_name'];
//        list($amount, $remark, $days) = self::getContractDayAmount($level->getDayAmount());
        //生成合同
        $view = self::getStaticDi()->get('view')->getRender('tools', 'contractTemplate', [
//            'sign' => is_null($sign_id) ? null : self::getImage($sign_id),
            'num' => $contract->getContractNum(),
            'name' => $company->getName(),
            'bank_account_name' => $company_info['bank_type'] == \Wdxr\Models\Repositories\CompanyInfo::BANK_TYPE_PUBLIC ? $company->getName() : $company_info['legal_name'],
            'type' => $company_info['type'] == RepoCompany::TYPE_COMPANY ? "非个体工商户" : "个体工商户",
            'contacts' => $company_info['contacts'],
            'contact_phone' => $company_info['contact_phone'],
//            'day_amount_chinese' => $amount,
//            'day_amount_remark' => $remark,
//            'days' => $days,
            'money' => intval($level->getLevelMoney()),
            'money_chinese' => self::num2rmb($level->getLevelMoney()),
            'address' => $address,
//            'bank_name' => $bank,
//            'bank_num' => $company_info['bankcard'],
            'payment_type' => $payment->getType(),
            'level' => $level->getId(),
            'legal_name' => $company_info['legal_name'],
            'licence_num' => $company_info['licence_num'],
            'scope' => $company_info['scope'],
            'period' => $company_info['period'],
            'sign_address' => $contract->getLocation(),
            'zip_code' => $company_info['zipcode'],
        ]);

        return $view;
    }

    static public function getContractView($company_id, $sign_id = null)
    {
        $company = RepoCompany::getCompanyById($company_id);
        if($company->getAuditing() != RepoCompany::AUDIT_OK) {
            throw new InvalidServiceException("当前企业尚未通过审核，无法查看合同");
        }
        if(($contract = RepoContract::getInUseContractNum($company_id)) === false) {
            throw new InvalidServiceException("合同信息获取失败");
        }
        $company_info = \Wdxr\Models\Repositories\CompanyInfo::getByCompanyId($company_id)->toArray();

        return self::generateContractView($company_info, $company, $contract, $sign_id);
    }

    /**
     * @param $company_info
     * @param Companys $company
     * @param Contracts $contract
     * @param $sign_id
     * @return mixed
     */
    static public function generateContractView($company_info, $company, $contract, $sign_id = null)
    {
        $company_id = $company->getId();
        $payment = RepoCompanyPayment::getPaymentByCompanyId($company_id);
        if($payment === false) {
            throw new InvalidServiceException("该企业尚未提交缴费信息");
        }
        $level = RepoLevel::getLevelByCompanyId($company_id);
        $address = RepoCompanyInfo::getDetailAddress($company_info['province'], $company_info['city'], $company_info['district'], $company_info['address']).$company_info['address'];
//        $bank = RepoCompanyInfo::getBankDetailAddress($company_info['bank_province'], $company_info['bank_city']).$company_info['bank_name'];
//        list($amount, $remark, $days) = self::getContractDayAmount($level->getDayAmount());
        list($start_time, $end_time) = CompanyService::getCompanyServiceTime($company_id, $payment->getType());
        $verify = \Wdxr\Models\Repositories\CompanyVerify::getLastCompanyVerify($company_id, \Wdxr\Models\Repositories\CompanyVerify::TYPE_DOCUMENTS, \Wdxr\Models\Repositories\CompanyVerify::STATUS_OK);
        //生成合同
        $view = self::getStaticDi()->get('view')->getRender('tools', 'contractTemplate', [
//            'sign' => is_null($sign_id) ? null : self::getImage($sign_id),
            'num' => $contract->getContractNum(),
            'name' => $company->getName(),
            'bank_account_name' => $company_info['bank_type'] == \Wdxr\Models\Repositories\CompanyInfo::BANK_TYPE_PUBLIC ? $company->getName() : $company_info['legal_name'],
            'type' => $company_info['type'] == RepoCompany::TYPE_COMPANY ? "非个体工商户" : "个体工商户",
            'contacts' => $company_info['contacts'],
            'contact_phone' => $company_info['contact_phone'],
            'start_time' => $start_time,
            'end_time' => $end_time,
//            'day_amount_chinese' => $amount,
//            'day_amount_remark' => $remark,
//            'days' => $days,
            'money' => intval($level->getLevelMoney()),
            'money_chinese' => self::num2rmb($level->getLevelMoney()),
            'address' => $address,
//            'bank_name' => $bank,
//            'bank_num' => $company_info['bankcard'],
            'payment_type' => $payment->getType(),
            'level' => $level->getId(),
            'legal_name' => $company_info['legal_name'],
            'licence_num' => $company_info['licence_num'],
            'scope' => $company_info['scope'],
            'period' => $company_info['period'],
            'sign_address' => $payment->getType() == \Wdxr\Models\Repositories\CompanyPayment::TYPE_LOAN ? '' : $contract->getLocation(),
            'zip_code' => $company_info['zipcode'],
            'verify_time' => $payment->getType() == \Wdxr\Models\Repositories\CompanyPayment::TYPE_LOAN ? '' : $verify->getVerifyTime(),
        ]);

        return $view;
    }

    static public function generateEmptyContractView()
    {
        $view = self::getStaticDi()->get('view')->getRender('tools', 'contractTemplate', [
            'sign' => '',
            'num' => '',
            'name' => '',
            'bank_account_name' => '',
            'type' => '',
            'contacts' => '',
            'contact_phone' => '',
            'start_time' => '',
            'end_time' => '',
            'day_amount_chinese' => '',
            'day_amount_remark' => '',
            'days' => '',
            'money' => '',
            'money_chinese' => '',
            'address' => '',
            'bank_name' => '',
            'bank_num' => '',
            'payment_type' => '',
            'level' => '',
            'legal_name' => '',
            'licence_num' => '',
            'scope' => '',
            'period' => '',
            'sign_address' => '',
            'zip_code' => '',
            'verify_time' => '',
        ]);

        return $view;
    }

    static public function getContractDayAmount($amount)
    {
        $amount_chinese = mb_substr(self::num2rmb($amount), 0, -2);
        if($amount == 300) {
            $remark = self::num2rmb(60).'X65天';
            $days = 300;
        } else {
            $remark = '无';
            $days = 365;
        }

        return [$amount_chinese, $remark, $days];
    }


    /**
     * 获取保存合同Object
     * @param string $contract_num
     * @param $object_path
     * @return string
     * @throws InvalidServiceException
     */
    static public function getContractObject($contract_num, $object_path)
    {
        $object = $object_path."/".$contract_num.".html";
        $path = self::getContractPath($object_path);
        if(file_exists($path) === false) {
            if(mkdir($path, 0777, true) === false) {
                throw new InvalidServiceException("合同文件夹创建失败");
            }
        }

        return $object;
    }

    static public function getContractPath($object)
    {
        return BASE_PATH."/contract/".$object;
    }

    /**
     * 生成一个新的合同编号，序号从2018年5月30日起算
     * @return bool
     */
    static public function getNextContractNum()
    {
        $contract = Repositories::getRepository('Contract');

        $time = mktime(0,0,0,5, 30, 2018);
        $contracts = $contract->getTimeContracts($time);
        $all_contracts = $contract->getTimeContracts();

        $prefix = str_pad(count($all_contracts) + 1, 7,0, STR_PAD_LEFT);
        $end = str_pad(count($all_contracts) - count($contracts) + 1, 4, 0, STR_PAD_LEFT);

        $contract_num = $prefix . "【".date('Y')."】年第" . $end . '号';
        $contract = new EntityContract();
        $contract->setContractNum($contract_num);
        return $contract->save();
    }

    static public function setContractPdf($title, $view)
    {
        $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'.$title.'.pdf"');
        $snappy->setOption('title', $title);
        $snappy->setOption('margin-top', 18);
        $snappy->setOption('header-line', true);
        $snappy->setOption('header-spacing', 2);
        $snappy->setOption('print-media-type', true);
//        $snappy->setOption('grayscale', true);
        $snappy->setOption('header-html', APP_PATH . '/modules/admin/views/tools/contract_header.html');
//        $snappy->setOption('header-right', '文本编码：WDXR-HT2017(02)');
//        $snappy->setOption('header-font-size', 9);
//        $snappy->setOption('header-font-name', 'SimSun');
        $snappy->setOption('footer-font-name', 'SimSun');
        $snappy->setOption('footer-right', '[page]/[topage]');
        $snappy->setOption('footer-font-size', 10);

        echo $snappy->getOutputFromHtml($view);
    }

}
