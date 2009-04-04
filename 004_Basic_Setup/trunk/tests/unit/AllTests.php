<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'SF_Unit_AllTests::main');
}

/**
 * TestHelper
 */
require_once dirname(__FILE__) . '/../TestHelper.php';

/**
 * Include unit tests
 */
require_once 'unit/Model/ModelAbstractTest.php';

class SF_Unit_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Storefront Unit Tests');
        $suite->addTestSuite('ModelAbstractTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'SF_Unit_AllTests::main') {
    SF_Unit_AllTests::main();
}                     