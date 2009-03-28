<?php
/**
 * Modified TestHelper for the storefront application
 * 
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @author 	   Keith Pope
 */

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
$root  = realpath(dirname(__FILE__) . '/../');
$paths = array(
    get_include_path(),
    "$root/library/Incu",
    "$root/library",
    "$root/tests",
    "$root/application"
);
set_include_path(implode(PATH_SEPARATOR, $paths));

defined('APPLICATION_PATH')
    or define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

/**
 * Autoloader helpers
 */
function _SF_Autloader_SetUp() {
    require_once 'Zend/Loader/Autoloader.php';
    $loader = Zend_Loader_Autoloader::getInstance();
    $loader->registerNamespace('SF_');
}

function _SF_Autloader_TearDown() {
    Zend_Loader_Autoloader::resetInstance();
    $loader = Zend_Loader_Autoloader::getInstance();
    $loader->registerNamespace('SF_');
}

/**
 * Init autoloader
 */
_SF_Autloader_SetUp();

/**
 * Start session now!
 */
Zend_Session::start();

/**
 * Ignore folders from code coverage etc
 */
PHPUnit_Util_Filter::addDirectoryToFilter("$root/tests");
PHPUnit_Util_Filter::addDirectoryToFilter("$root/library/Zend");
