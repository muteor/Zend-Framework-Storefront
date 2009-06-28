<?php
/**
 * Get PHPUnit
 */
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Framework/IncompleteTestError.php';
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/Runner/Version.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Util/Filter.php';

/*
 * Set error reporting level
 */
error_reporting( E_ALL | E_STRICT );

/**
 * Default timezone
 */
date_default_timezone_set('Europe/London');

/*
 * Set the include path
 */
$root  = realpath(dirname(__FILE__) . '/../../');
$paths = array(
    get_include_path(),
    "$root/library",
    "$root/tests",
    "$root/application"
);
set_include_path(implode(PATH_SEPARATOR, $paths));

defined('APPLICATION_PATH')
    or define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

/**
 * Get the base test case
 */
require_once 'ControllerTestCase.php';

/**
 * Start session now!
 */
Zend_Session::$_unitTestEnabled = true;
Zend_Session::start();

/**
 * Ignore folders from code coverage etc
 */
PHPUnit_Util_Filter::addDirectoryToFilter("$root/tests");
PHPUnit_Util_Filter::addDirectoryToFilter("$root/library/Zend");
