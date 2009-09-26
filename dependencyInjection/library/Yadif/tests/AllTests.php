<?php

require_once dirname(__FILE__)."/../Container.php";
require_once dirname(__FILE__)."/../Builder.php";
require_once dirname(__FILE__)."/../Module.php";
require_once "PHPUnit/Framework.php";

require_once "YadifConfigComponentTest.php";
require_once "YadifInstantiateObjectGraphTest.php";
require_once "YadifBindParamsTest.php";
require_once "YadifEnforceSingletonTest.php";
require_once "YadifZendApplicationComplianceTest.php";
require_once "YadifBuilderTest.php";
require_once "YadifModuleTest.php";

class AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Yadif - Testsuite');
        $suite->addTestSuite('YadifConfigComponentTest');
        $suite->addTestSuite('YadifInstantiateObjectGraphTest');
        $suite->addTestSuite('YadifBindParamsTest');
        $suite->addTestSuite('YadifEnforceSingletonTest');
        $suite->addTestSuite('YadifZendApplicationComplianceTest');
        $suite->addTestSuite('YadifBuilderTest');
        $suite->addTestSuite('YadifModuleTest');

        return $suite;
    }
}