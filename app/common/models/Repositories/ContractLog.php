<?php
namespace Wdxr\Models\Repositories;


class ContractLog
{

    const TYPE_CREATE = 1;
    const TYPE_VIEW = 2;
    const TYPE_DOWNLOAD = 3;
    const TYPE_DELETE = 4;
    const TYPE_EDIT = 5;

    static public $_type_name = [
        self::TYPE_CREATE => '创建',
        self::TYPE_VIEW => '查看',
        self::TYPE_DOWNLOAD => '下载',
        self::TYPE_DELETE => '删除',
        self::TYPE_EDIT => '修改'
    ];

}