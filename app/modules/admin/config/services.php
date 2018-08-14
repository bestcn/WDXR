<?php

use Phalcon\Mvc\View;
use Wdxr\Modules\Admin\Plugins\Security;


$di->set('dispatcher', function() use ($di) {
    /**
     * @var $eventsManager Phalcon\Events\Manager
     */
    $eventsManager = $di->getShared('eventsManager');

    $security = new Security($di);

    /**
     * We listen for events in the dispatcher using the Security plugin
     */
    $eventsManager->attach('dispatch', $security);

    $dispatcher = new Phalcon\Mvc\Dispatcher();
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setViewsDir($config->get('application')->viewsDir);

    $view->registerEngines([
        '.volt'  => 'voltShared',
    ]);

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $dbConfig = $config->database->toArray();

    $dbAdapter = '\Phalcon\Db\Adapter\Pdo\\' . $dbConfig['adapter'];
    unset($config['adapter']);

    return new $dbAdapter($dbConfig);
});

/**
 * register the captcha service
 */
$di->setShared('captcha', function () {
    $captcha = new \Wdxr\Captcha();
    $captcha->type = "captcha_";
    $captcha->code_len = 4;
    $captcha->font_size = 18;
    $captcha->width = 100;
    $captcha->height = 30;
    return $captcha;
});

$di->set('auth', function () {
    return new Wdxr\Auth\Auth();
});

/**
 * Register the flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Phalcon\Flash\Session([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

$di->set("menu", function () {
    return new \Wdxr\Modules\Admin\Tags\MenuTags();
});

$di->setShared('PHPExcel', function () {
    require_once APP_PATH.'/modules/admin/plugins/PHPExcel.php';
    return new PHPExcel();
});