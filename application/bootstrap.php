<?php
$paths = array(
    get_include_path(),
    '../library',
    '../library/Incu',
    '../library/Zapp'
);
set_include_path(join(PATH_SEPARATOR, array_reverse($paths)));

defined('APPLICATION_PATH')
    or define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
defined('APPLICATION_ENV')
    or define('APPLICATION_ENV', 'development');
    
require_once 'Zend/Application.php';

/**
 * Now there are multiple ways to set up the application. Firstly, the way via
 * options or Zend_Config:
 */
$application = new Zend_Application(APPLICATION_ENV, array(
    'bootstrap' => APPLICATION_PATH . '/bootstrap/Bootstrap.php',
    'autoloadernamespaces' => array('Zend', 'SF')
    )
);

$application->bootstrap();

var_dump($application);