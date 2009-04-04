<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'AllTests::main');
}

/**
 * TestHelper
 */
require_once 'TestHelper.php';

/**
 * @see SF_Unit_AllTests
 */
require_once 'unit/AllTests.php';

class AllTests
{
    public static function main()
    {
        $parameters = array();

        PHPUnit_TextUI_TestRunner::run(self::suite(), $parameters);
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Storefront');

        $suite->addTest(SF_Unit_AllTests::suite());

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'AllTests::main') {
    AllTests::main();
}