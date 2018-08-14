<?php
namespace Wdxr\Modules\Api\Forms;

use Phalcon\Validation\Validator\BankCard;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\PresenceOf;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Models\Repositories\CompanyBank as RepoCompanyBank;

class PaymentValidation extends \Phalcon\Validation
{

    public function initialize()
    {

        $this->add('company_id', new PresenceOf([
            'message' => '企业参数错误',
        ]));

        $this->add('type', new PresenceOf([
            'message' => '请选择缴费方式'
        ]));

        $this->add('voucher', new PresenceOf([
            'message' => '请上传缴费凭证'
        ]));

        $this->add('bankcard_photo',
            new PresenceOf([
                'message' => '请上传银行卡照片',
                "cancelOnFail" => true,
            ])
        );

        $this->add('number',
            new PresenceOf([
                'message' => '请填写银行卡号',
            ])
        );

        $this->add('number',
            new BankCard([
                'message' => '银行卡号不正确',
            ])
        );

        $this->add('account',
            new PresenceOf([
                'message' => '请填写开户人',
            ])
        );

        $this->add('province',
            new PresenceOf([
                'message' => '请选择开户行所在省份',
            ])
        );

        $this->add('city',
            new PresenceOf([
                'message' => '请选择开户行所在城市',
            ])
        );

        $this->add('bank_type',
            new PresenceOf([
                'message' => '请选择银行账户类型',
            ])
        );

        $this->add('bank_type',
            new InclusionIn([
                'message' => '银行账户类型选择错误',
                'domain' => [RepoCompanyBank::TYPE_PUBLIC, RepoCompanyBank::TYPE_PRIVATE]
            ])
        );

        $this->add('bank', new PresenceOf([
            'message' => '请选择开户银行',
        ]));

        $this->add('address', new PresenceOf([
            'message' => '请填写详细的开户行',
        ]));

        if($this->request->getPost('bank_type') == RepoCompanyBank::TYPE_PUBLIC) {
            $this->add('work_number',
                new PresenceOf([
                    'message' => '请填写绩效银行卡号',
                ])
            );

            $this->add('work_number',
                new BankCard([
                    'message' => '绩效银行卡号格式错误',
                ])
            );

            //绩效卡开户人
            $this->add('work_account', new PresenceOf([
                'message' => '请填写绩效银行卡开户人',
            ]));

            $this->add('work_province', new PresenceOf([
                'message' => '请选择开户行所在省份',
            ]));

            $this->add('work_city', new PresenceOf([
                'message' => '请选择开户行所在城市',
            ]));

            $this->add('work_bank', new PresenceOf([
                'message' => '请选择开户银行',
            ]));

            $this->add('work_address', new PresenceOf([
                'message' => '请填写详细的开户行 ',
            ]));
        }
    }
}
