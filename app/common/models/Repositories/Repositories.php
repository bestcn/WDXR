<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Repositories\Exceptions;

class Repositories extends \Phalcon\Di\Injectable implements \Phalcon\Mvc\ControllerInterface
{
    public static function getRepository($name)
    {
        $className = __NAMESPACE__."\\{$name}";
        
        if (! class_exists($className)) {
            throw new Exceptions\InvalidRepositoryException("Repository {$className} doesn't exists.");
        }
        
        return new $className();
    }
}
