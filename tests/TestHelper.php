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
$tests = "$root/tests";
$app   = "$root/application";
$lib   = "$root/library"; 

$path = array(
    $root,
    $tests,
    $app,
    $lib,
    get_include_path()
    );
set_include_path(implode(PATH_SEPARATOR, $path));

/**
 * Register the autoload
 */
require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload();

/**
 * Start session now!
 */
Zend_Session::start();

/**
 * Set root
 */
Zend_Registry::set('root', $root);

/**
 * Ignore folders from code coverage etc
 */
PHPUnit_Util_Filter::addDirectoryToFilter($tests);
PHPUnit_Util_Filter::addDirectoryToFilter("$lib/Zend");
