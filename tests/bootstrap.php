<?php
/**
 * Error reporting
 */
error_reporting( E_ALL | E_STRICT );

/**
 * Paths
 */
$root  = realpath(dirname(dirname(__FILE__)));
$lib   = "$root/library";
$tests = "$root/tests";

$path = array(
    $lib,
    $tests,
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $path));

require_once 'Mockery.php';
require_once 'Zend/Loader/Autoloader.php';

$autoloader = Zend\Loader\Autoloader::getInstance();
$autoloader->registerNamespace('Zend');
$autoloader->registerNamespace('PHPUnit');
$autoloader->registerNamespace('Mockery');
$autoloader->registerNamespace('SF');

$resourceAutoloader = new Zend\Loader\ResourceAutoloader(array(
    'basePath'  => "$root/application/modules/storefront",
    'namespace' => "Storefront"
));
$resourceAutoloader->addResourceTypes(array(
    'modelResource' => array(
      'path'      => 'models/resources',
      'namespace' => 'Model\\Resource',
    ),
    'document' => array(
        'path' => 'models/document',
        'namespace' => 'Model\\Document'
    ),
    'dbtable' => array(
        'namespace' => 'Model\\DbTable',
        'path'      => 'models/DbTable',
    ),
    'mappers' => array(
        'namespace' => 'Model\\Mapper',
        'path'      => 'models/mappers',
    ),
    'form'    => array(
        'namespace' => 'Form',
        'path'      => 'forms',
    ),
    'model'   => array(
        'namespace' => 'Model',
        'path'      => 'models',
    ),
    'plugin'  => array(
        'namespace' => 'Plugin',
        'path'      => 'plugins',
    ),
    'service' => array(
        'namespace' => 'Service',
        'path'      => 'services',
    ),
    'viewhelper' => array(
        'namespace' => 'View\\Helper',
        'path'      => 'views/helpers',
    ),
    'viewfilter' => array(
        'namespace' => 'View\\Filter',
        'path'      => 'views/filters',
    ),
));