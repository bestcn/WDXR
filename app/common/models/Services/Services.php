<?php
namespace Wdxr\Models\Services;

use Wdxr\Models\Services\Exceptions;

class Services extends \Phalcon\Di\Injectable implements \Phalcon\Mvc\ControllerInterface
{

    /**
     * @var
     */
    private static $_instance = [];

    /**
     * @param $name
     * @return \Wdxr\Models\Services\{$name}
     * @throws Exceptions\InvalidServiceException
     */
    public static function getService($name)
    {
        $className = __NAMESPACE__."\\{$name}";
        
        if (! class_exists($className)) {
            throw new Exceptions\InvalidServiceException("Class {$className} doesn't exists.");
        }
        
        return new $className();
    }

    /**
     * @return \Phalcon\Mvc\Model\Manager|\Phalcon\Mvc\Model\ManagerInterface
     */
    public static function getStaticModelsManager()
    {
        return \Phalcon\Di::getDefault()->get('modelsManager');
    }

    /**
     * @return \Phalcon\DiInterface
     */
    public static function getStaticDi()
    {
        return \Phalcon\Di::getDefault();
    }

    /**
     * @return \Phalcon\Config
     */
    public static function getStaticConfig()
    {
        return self::getStaticDI()->get('config');
    }

    /**
     * @return \Phalcon\Cache\Backend\Redis
     */
    protected static function getRedis()
    {
        return \Phalcon\Di::getDefault()->get('redis');
    }

    /**
     * @param $service
     * @return mixed
     */
    public static function Hprose($service)
    {
        $config = self::getStaticDi()->get('config');
        $prefix = $config->get('hprose')->get('prefix');
        if (isset(self::$_instance[$service]) === false) {
            self::$_instance[$service] = \Hprose\Client::create($prefix.$service, false);
            self::$_instance[$service]->setHeader("Hprose-Call-Key", self::getHproseHeaderKey());
        }
        return self::$_instance[$service];
    }

    public static function getHproseHeaderKey()
    {
        $config_key = self::getStaticDi()->get('config')->hprose->key;
        $key = self::getStaticDi()->get('security')->hash($config_key);

        return $key;
    }

}
