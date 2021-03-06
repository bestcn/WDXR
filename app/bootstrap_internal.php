<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers the services that
     * provide a full stack framework. These default services can be overidden with custom ones.
     */
    $di = new FactoryDefault();

    /**
     * Include general services
     */
    require APP_PATH . '/config/services.php';

    /**
     * Include web environment specific services
     */
    require APP_PATH . '/config/services_internal.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Handle the request
     */
    $application = new Application($di);

    /**
     * Register application modules
     */
    $application->registerModules([
        'admin'     => ['className' => 'Wdxr\Modules\Admin\Module'],
    ]);

    /**
     * Include routes
     */
    require APP_PATH . '/config/routes_internal.php';

    echo $application->handle()->getContent();

} catch (\Exception $e) {
    $error = json_encode(['title' => $e->getMessage(), 'trace' => $e->getTrace()]);
    $di['json_logger']->error($error);
    echo "网络错误";
}
