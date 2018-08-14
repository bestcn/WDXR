<?php

use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () use($di) {
    $config = $this->getConfig();

    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    $connection = new Phalcon\Db\Adapter\Pdo\Mysql($params);
    return $connection;
});

$di->setShared('old_db', function () use($di) {
    $config = $this->getConfig();

    $params = [
        'host'     => $config->old_database->host,
        'username' => $config->old_database->username,
        'password' => $config->old_database->password,
        'dbname'   => $config->old_database->dbname,
        'charset'  => $config->old_database->charset
    ];

    $connection = new Phalcon\Db\Adapter\Pdo\Mysql($params);
    return $connection;
});

$di->set('acl', function() {
    $connection = $this->getDb();
    $acl = new Phalcon\Acl\Adapter\Database([
        'db'                => $connection,
        'roles'             => 'roles',
        'rolesInherits'     => 'roles_inherits',
        'resources'         => 'resources',
        'resourcesAccesses' => 'resources_accesses',
        'accessList'        => 'access_list'
    ]);
    $acl->setDefaultAction(Phalcon\Acl::DENY);
    $acl->setNoArgumentsDefaultAction(Phalcon\Acl::DENY);

    return $acl;
});

$di->setShared('modelsCache', function () {
    $config = $this->getConfig();

    $front = new Phalcon\Cache\Frontend\Data([
        "lifetime" => 86400,
    ]);
    $redis_config = [
        "host"       => $config->redis->host,
        "port"       => $config->redis->port,
        "persistent" => $config->redis->persistent,
        "index"      => 0,
        "statsKey"   => '_WDXR',
    ];
    if($config->redis->password) {
        $redis_config['auth'] = $config->redis->password;
    }
    return new Phalcon\Cache\Backend\Redis($front, $redis_config);
});

$di->setShared('redis', function () {
    $config = $this->getConfig();

    $front = new Phalcon\Cache\Frontend\Data([
        "lifetime" => 86400,
    ]);
    $redis_config = [
        "host"       => $config->redis->host,
        "port"       => $config->redis->port,
        "persistent" => $config->redis->persistent,
        "index"      => 0,
        "statsKey"   => '_WDXR',
    ];
    if($config->redis->password) {
        $redis_config['auth'] = $config->redis->password;
    }
    return new Phalcon\Cache\Backend\Redis($front, $redis_config);
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Configure the Volt service for rendering .volt templates
 */
$di->setShared('voltShared', function ($view) {
    $config = $this->getConfig();

    $volt = new VoltEngine($view, $this);
    $volt->setOptions([
        //when debug set the option compileAlways to false
        'compileAlways' => true,
        'compiledSeparator' => '_',
        'compiledPath' => $config->application->cacheDir . 'volt/',
    ]);

    $compiler = $volt->getCompiler();
    $compiler->addFunction('acl_button', function ($resolvedArgs, $exprArgs) {
        return "Wdxr\\Modules\\Admin\\Tags\\MenuTags::acl_button($resolvedArgs);";
    });
    $compiler->addFunction('acl_menu', function ($resolvedArgs) {
        return "Wdxr\\Modules\\Admin\\Tags\\MenuTags::acl_menu($resolvedArgs);";
    });
    $compiler->addFunction('acl_group', function ($resolvedArgs) {
        return "Wdxr\\Modules\\Admin\\Tags\\MenuTags::acl_group($resolvedArgs);";
    });
    $compiler->addFunction('get_url', function ($resolvedArgs) {
        return "\Wdxr\Models\Repositories\Attachment::getAttachmentUrl($resolvedArgs)";
    });
    $compiler->addFunction('get_address', function ($resolvedArgs) {
        return "\Wdxr\Models\Repositories\Regions::getAddress($resolvedArgs)";
    });
    $compiler->addFunction('get_status_name', function ($resolvedArgs) {
        return "\Wdxr\Models\Repositories\CompanyService::getStatusName($resolvedArgs)";
    });

    return $volt;
});

$di->setShared('logger', function () {
    $name = BASE_PATH."/cache/logs/".date("Ymd").".log";
    return new FileAdapter($name);
});

$di->setShared('json_logger', function () {
    $name = BASE_PATH."/cache/logs/json_".date("Ymd").".log";
    $logger = new FileAdapter($name);
    $formatter = new LineFormatter('{"date":"%date%","type":"%type%","message":%message%}');
    $logger->setFormatter($formatter);

    return $logger;
});
