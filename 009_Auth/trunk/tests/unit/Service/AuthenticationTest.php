<?php
/**
 * TestHelper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

/**
 * Storefront_Service_Authenticationl
 */
require_once 'modules/storefront/services/Authentication.php';

/**
 * Test case for Storefront_Service_Authentication
 */
class AuthenticationTest extends PHPUnit_Framework_TestCase
{
    protected $_service;
    protected $_db;
    
    protected function setUp()
    {
        $this->_service = new Storefront_Service_Authentication();
        
        // setup in memory db so we get no Zend_Db errors (we dont need any schema!)
        $this->_db = new Zend_Db_Adapter_Pdo_Sqlite(array('dbname'   => ':memory:'));
        Zend_Db_Table_Abstract::setDefaultAdapter($this->_db);
    }
    
    protected function tearDown()
    {
        Zend_Db_Table_Abstract::setDefaultAdapter(null);
        $this->_service = null;
        $this->_db = null;
    }
    
    public function test_Get_Adpater_Returns_Zend_Auth_Adapter_DbTable()
    {
        $this->assertType('Zend_Auth_Adapter_DbTable', $this->_service->getAuthAdapter(
                array('email' => '', 
                    'passwd' => ''
                )
            )
        );
    }
    
    public function test_Authenticate_Returns_False_On_Invalid_Credentials()
    {
        $mockAdapter = $this->getMock('Zend_Auth_Adapter_DbTable', array(), array(), '', false);
        $mockAdapter
            ->expects($this->once())
            ->method('authenticate')
            ->will(
                $this->returnValue(
                    new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,false)
                )
            );

        $this->_service->setAuthAdapter($mockAdapter);
        $this->assertFalse($this->_service->authenticate(array('email' => 'test@test.com', 'passwd' => 'moo')));
    }
    
    public function test_Authenticate_Returns_True_On_Valid_Credentials()
    {
        $mockAdapter = $this->getMock('Zend_Auth_Adapter_DbTable', array(), array(), '', false);
        $mockAdapter
            ->expects($this->once())
            ->method('authenticate')
            ->will(
                $this->returnValue(
                    new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,new stdClass())
                )
            );

        $this->_service->setAuthAdapter($mockAdapter);
        $this->assertTrue($this->_service->authenticate(array('email' => 'test@test.com', 'passwd' => 'moo')));
    }
    
    public function test_Authenticate_Saves_Identity_To_Session()
    {
        $ident = new stdClass();
        $ident->userId = 1;

        $mockUser = $this->getMock('Storefront_Model_User');
        $mockUser
            ->expects($this->once())
            ->method('getUserByEmail')
            ->will($this->returnValue(true));
            
        $mockAdapter = $this->getMock('Zend_Auth_Adapter_DbTable', array(), array(), '', false);
        $mockAdapter
            ->expects($this->once())
            ->method('authenticate')
            ->will(
                $this->returnValue(
                    new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, new stdClass())
                )
            );
            
        $this->_service = new Storefront_Service_Authentication($mockUser);
        $this->_service->setAuthAdapter($mockAdapter);
        $this->assertTrue($this->_service->authenticate(array('email' => 'test@test.com', 'passwd' => 'moo')));
        
        $auth = Zend_Auth::getInstance();
        $this->assertEquals(true, $auth->getIdentity());
    }
    
    public function test_Authenticate_Can_Clear_Identity()
    {
        $ident = new stdClass();
        $ident->userId = 1;
        
        $mockUser = $this->getMock('Storefront_Model_User');
        $mockUser
            ->expects($this->once())
            ->method('getUserByEmail')
            ->will($this->returnValue(true));

        $mockAdapter = $this->getMock('Zend_Auth_Adapter_DbTable', array(), array(), '', false);
        $mockAdapter
            ->expects($this->once())
            ->method('authenticate')
            ->will(
                $this->returnValue(
                    new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, new stdClass())
                )
            );
            
        $this->_service = new Storefront_Service_Authentication($mockUser);
        $this->_service->setAuthAdapter($mockAdapter);
        $this->assertTrue($this->_service->authenticate(array('email' => 'test@test.com', 'passwd' => 'moo')));
        
        $auth = Zend_Auth::getInstance();
        
        $this->assertEquals(true, $auth->getIdentity());
        
        $this->_service->clear();
        
        $this->assertFalse($auth->hasIdentity());
    }
}