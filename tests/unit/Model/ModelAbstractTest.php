<?php

/**
 * TestHelper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

/**
 * Concrete class for testing
 */
class MyTestModel extends SF_Model_Abstract 
{
    protected function _loadResource($name)
    {
        return new $name();
    }

    public function getPluginLoader(){}
}

/**
 * Test resources to load
 */
class My_Resource extends Zend_Db_Table {
    protected $_db = true;
}
class MyOtherResource {}

/**
 * Make sure we dont affect the standard registry
 */
Zend_Registry::set('testconflict', true);

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
        $this->_model = new MyTestModel();   
    }
    
    protected function tearDown()
    {
        SF_Model_Resource_Registry::clear();
    }

    public function test_Model_Can_Add_New_Resource()
    {
        $this->_model->addResource('my_resource');
        
        $this->assertNotNull( Zend_Registry::get('SFR_MyTestModel_My_Resource') );   
    }
    
    public function test_Model_Does_Not_Accept_Null_Resources()
    {
        $this->setExpectedException('SF_Model_Exception');
        
        $this->_model->addResource('');
    }
    
    public function test_Model_Can_Retrieve_Registered_Resource()
    {
        $this->_model->addResource('my_resource');
        $this->assertType('My_Resource',$this->_model->getResource('my_resource'));
    }
    
    public function test_Model_Can_Retrieve_Default_Resource()
    {
        $this->_model->addResource('my_resource', true);
        $this->_model->addResource('MyOtherResource');
        
        $registry = SF_Model_Resource_Registry::getInstance();
        
        $this->assertType('My_Resource',$this->_model->getResource());
    }
    
    public function test_Model_Can_Not_Have_More_Than_One_Default()
    {
        $this->_model->addResource('my_resource', true);
        $this->_model->addResource('MyOtherResource', true);
        
        $this->assertType('MyOtherResource',$this->_model->getResource());
    }
    
    public function test_Model_Throws_If_Resource_Does_Not_Exist()
    {
        $this->setExpectedException('SF_Model_Exception');
        
        $this->_model->getResource('noresource');
    }
    
    public function test_Model_Can_Lock_Resources()
    {
        $container = new SF_Model_Resource_Registry_Container('MyTestModel_My_Resource', 'MyOtherResource', true, true);
        
        SF_Model_Resource_Registry::set('MyTestModel_My_Resource', $container);
        
        $this->_model = new MyTestModel();
        $this->assertType('MyOtherResource', $this->_model->getResource() );

        $this->_model->addResource('My_Resource', true);
        $this->assertType('MyOtherResource', $this->_model->getResource() );

    }
}