<?php

/**
 * @var $router \Phalcon\Mvc\Router
 */
$router = $di->getRouter();
$namespace = 'Wdxr\Modules\Admin\Controllers';

$router->add('/:params', [
    'namespace' => $namespace,
    'module' => 'admin',
    'controller' => 'Index',
    'action' => 'index',
    'params' => 1
]);
$router->add('/:controller/:params', [
    'namespace' => $namespace,
    'module' => 'admin',
    'controller' => 1,
    'action' => 'index',
    'params' => 2
]);
$router->add('/:controller/:action/:params', [
    'namespace' => $namespace,
    'module' => 'admin',
    'controller' => 1,
    'action' => 2,
    'params' => 3
]);
$router->add('/admin/:params', [
    'namespace' => $namespace,
    'module' => 'admin',
    'controller' => 'index',
    'action' => 'index',
    'params' => 1
])->setName('admin');
$router->add('/admin/:controller/:params', [
    'namespace' => $namespace,
    'module' => 'admin',
    'controller' => 1,
    'action' => 'index',
    'params' => 2
]);
$router->add('/admin/:controller/:action/:params', [
    'namespace' => $namespace,
    'module' => 'admin',
    'controller' => 1,
    'action' => 2,
    'params' => 3
]);
