<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'SF_Application_AllTests::main');
}

/**
 * TestHelper
 */
require_once dirname(__FILE__) . '/TestHelper.php';

/**
 * Get the tests
 */
require_once 'controllers/indexControllerTest.php';
require_once 'controllers/customerControllerTest.php';

class SF_Application_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Storefront Application Tests');
        $suite->addTestSuite('IndexControllerTest');
        $suite->addTestSuite('CustomerControllerTest');
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'SF_Application_AllTests::main') {
    SF_Unit_AllTests::main();
}                     