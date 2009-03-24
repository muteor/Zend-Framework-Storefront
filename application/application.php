<?php
$paths = array(
    get_include_path(),
    '../library/Incu',
     '../library',
);
set_include_path(implode(PATH_SEPARATOR, $paths));

defined('APPLICATION_PATH')
    or define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
defined('APPLICATION_ENV')
    or define('APPLICATION_ENV', 'development');

require_once 'Zend/Application.php';

$application = new Zend_Application(APPLICATION_ENV, array(
    'bootstrap' => APPLICATION_PATH . '/bootstrap/Bootstrap.php',
    'autoloadernamespaces' => array('Zend_', 'SF_'),
    'resources' => array(
        'frontcontroller' => array(
            'moduledirectory' => APPLICATION_PATH . '/modules',
            'defaultmodule' => 'storefront',
        ),
        'modules' => array(),
    ),
    'phpsettings' => array(
            'display_errors' => true,
            'error_reporting' => E_ALL|E_STRICT,
            'date.timezone' =>  'Europe/London',
        )
    )
);
$application->bootstrap();
$application->run();