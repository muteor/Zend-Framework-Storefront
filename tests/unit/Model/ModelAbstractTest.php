<?php
namespace SFTest\Model;

use SF\Model\AbstractModel as SFAbstractModel;

/**
 * Concrete class for testing
 */
class MyTestModel extends SFAbstractModel
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
require_once __DIR__ . '/TestResources/FormTest.php';
require_once __DIR__ . '/TestResources/ResourceTest.php';

/**
 * Test case for Model_Abstract
 * @group migration
 */
class ModelAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SF_Model_Interface
     */
    protected $_model;
    
    protected function setUp()
    {
        $this->_model = new MyTestModel();
    }
    
    protected function tearDown()
    {
        $this->_model = null;
    }

    public function test_Constructor_Can_Set_Options()
    {
        $options = array('test' => true);
        $model = new MyTestModel($options);
        $this->assertTrue($this->readAttribute($model, '_testSetter'));
    }

    public function test_GetResource_Tries_To_Instantiate_The_Correct_Resource()
    {
        $this->assertType('SFTest\\Model\\Resource\\TestResource', $this->_model->getResource('test'));
    }

    public function test_GetForm_Tries_To_Instantiate_The_Correct_Form()
    {
        $this->assertType('SFTest\\Form\\Test', $this->_model->getForm('test'));
    }
}