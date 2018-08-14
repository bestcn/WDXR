<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * Register Namespaces
 */
$loader->registerNamespaces([
    'Wdxr\Models'       => APP_PATH . '/common/models/',
    'Wdxr'              => APP_PATH . '/common/library/Wdxr/',
    'Phalcon'           => APP_PATH . '/common/library/Phalcon/',
    'duncan3dc'         => APP_PATH . '/common/library/duncan3dc/',
    'Lcobucci'          => APP_PATH . '/common/library/Lcobucci/',
    'GuzzleHttp'        => APP_PATH . '/common/library/GuzzleHttp/',
    'Psr'               => APP_PATH . '/common/library/Psr/',
    'Knp'               => APP_PATH . '/common/library/Knp/',
    'Symfony'           => APP_PATH . '/common/library/Symfony/',
    'Push'              => APP_PATH . '/common/library/AliyunSDK/aliyun-php-sdk-push/Push/',
    'Category'          => APP_PATH . '/common/library/Category/',
    'QCloud'            => APP_PATH . '/common/library/QCloud/',
    'XingeApp'          => APP_PATH . '/common/library/XingeApp/',
]);

$loader->registerFiles([
    APP_PATH . '/common/library/Hprose/Hprose.php',
    APP_PATH . '/common/library/GuzzleHttp/functions.php',
    APP_PATH . '/common/library/GuzzleHttp/Psr7/functions.php',
    APP_PATH . '/common/library/GuzzleHttp/Promise/functions.php',
    APP_PATH . '/common/library/AliyunSDK/aliyun-php-sdk-core/Config.php',
    APP_PATH . '/common/library/phpqrcode/phpqrcode.php',
]);

/**
 * Register module classes
 */
$loader->registerClasses([
    'Wdxr\Modules\Frontend\Module' => APP_PATH . '/modules/frontend/Module.php',
    'Wdxr\Modules\Admin\Module'    => APP_PATH . '/modules/admin/Module.php',
    'Wdxr\Modules\Cli\Module'      => APP_PATH . '/modules/cli/Module.php',
    'Wdxr\Modules\Api\Module'      => APP_PATH . '/modules/api/Module.php',
    'Wdxr\Modules\Company\Module'  => APP_PATH . '/modules/company/Module.php',
    'PHPExcel'                     => APP_PATH . '/common/library/PHPExcel.php',
]);

$loader->register();
