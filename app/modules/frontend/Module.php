<?php
namespace Wdxr\Modules\Frontend;

use Phalcon\Config;
use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\ModuleDefinitionInterface;

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
            'Wdxr\Modules\Frontend\Controllers' => __DIR__ . '/controllers/',
            'Wdxr\Modules\Frontend\Models' => __DIR__ . '/models/',
            'Wdxr\Modules\Frontend\Forms' => __DIR__ . '/forms',
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

        if(file_exists(__DIR__. '/config/services.php')) {
            require __DIR__ . '/config/services.php';
        }
    }
}
