<?php

namespace Wdxr\Modules\Api;

use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Config;
use Wdxr\Modules\Api\Plugins\Security;


class Module implements ModuleDefinitionInterface
{
    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();

        $loader->registerNamespaces([
            'Wdxr\Modules\Api\Controllers' => __DIR__ . '/controllers/',
            'Wdxr\Modules\Api\Models'      => __DIR__ . '/models/',
            'Wdxr\Modules\Api\Forms'       => __DIR__ . '/forms',
            'Wdxr\Modules\Api\Plugins'     => __DIR__ . '/plugins',
        ]);

        $loader->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        /**
         * Try to load local configuration
         */
        if (file_exists(__DIR__ . '/config/config.php')) {
            
            $config = $di['config'];
            
            $override = new Config(include __DIR__ . '/config/config.php');

            if ($config instanceof Config) {
                $config->merge($override);
            } else {
                $config = $override;
            }
        }


        $di->set('dispatcher', function() use ($di) {
            $eventsManager = $di->getShared('eventsManager');

            $security = new Security($di);
            $eventsManager->attach('dispatch', $security);

            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        });

        /**
         * Setting up the view component
         */
        $di['view'] = function () {
            $config = $this->getConfig();

            $view = new View();
            $view->setDI($this);
            $view->setViewsDir($config->get('application')->viewsDir);

            $view->registerEngines([
                '.volt'  => 'voltShared',
            ]);

            $view->disable();
            return $view;
        };


        $di->set('auth', function () {
            return new \Wdxr\Auth\Auth();
        });

        $di->setShared('guzzle', function () {
            return new \GuzzleHttp\Client();
        });

        /**
         * Database connection is created based in the parameters defined in the configuration file
         */
        $di['db'] = function () {
            $config = $this->getConfig();

            $dbConfig = $config->database->toArray();

            $dbAdapter = '\Phalcon\Db\Adapter\Pdo\\' . $dbConfig['adapter'];
            unset($config['adapter']);

            return new $dbAdapter($dbConfig);
        };
    }
}
