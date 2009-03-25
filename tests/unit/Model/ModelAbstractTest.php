<?php
/**
 * TestHelper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

/**
 * Concrete class for testing
 */
class My_TestModel extends SF_Model_Abstract
{
    protected $_testSetter;

    public function setTest($value)
    {
        $this->_testSetter = $value;
    }
}

/**
 * Test resources to load
 */
class My_Resource_Test {}
class My_Resource_Test_Me {}
class My_Form_Test {}
class My_Form_Test_Me {}

/**
 * Test case for Model_Abstract
 */
class ModelAbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SF_Model_Interface
     */
    protected $_model;
    
    protected function setUp()
    {
        $this->_model = new My_TestModel();
    }
    
    protected function tearDown()
    {
        $this->_model = null;
    }

    public function test_Constructor_Can_Set_Options()
    {
        $options = array('test' => true);
        $model = new My_TestModel($options);
        $this->assertTrue($this->readAttribute($model, '_testSetter'));
    }

    public function test_GetResource_Tries_To_Instantiate_The_Correct_Resource()
    {
        $this->assertType('My_Resource_Test', $this->_model->getResource('test'));
    }

    public function test_GetForm_Tries_To_Instantiate_The_Correct_Form()
    {
        $this->assertType('My_Form_Test', $this->_model->getForm('test'));
    }

    public function test_GetMethods_Inflect_As_Expected()
    {
        $this->assertType('My_Form_Test_Me', $this->_model->getForm('testMe'));
        $this->assertType('My_Resource_Test_Me', $this->_model->getResource('testMe'));
    }
}