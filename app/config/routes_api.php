<?php

/**
 * @var $router \Phalcon\Mvc\Router
 */
$router = $di->getRouter();
$namespace = 'Wdxr\Modules\Api\Controllers';

$router->add('/:params', [
    'namespace' => $namespace,
    'module' => 'api',
    'controller' => 'Index',
    'action' => 'index',
    'params' => 1
]);
$router->add('/:controller/:params', [
    'namespace' => $namespace,
    'module' => 'api',
    'controller' => 1,
    'action' => 'index',
    'params' => 2
]);
$router->add('/:controller/:action/:params', [
    'namespace' => $namespace,
    'module' => 'api',
    'controller' => 1,
    'action' => 2,
    'params' => 3
]);
