<?php
namespace SFTest\Service;

use Storefront\Service;

/**
 * Test case for Storefront_Service_Authentication
 */
class AuthenticationTest extends \PHPUnit_Framework_TestCase
{
    protected $_service;
    protected $_db;
    
    protected function setUp()
    {
        $this->_service = new Service\Authentication();
        
        // setup in memory db so we get no Zend_Db errors (we dont need any schema!)
        $this->_db = new \Zend\Db\Adapter\Pdo\Sqlite(array('dbname'   => ':memory:'));
        \Zend\Db\Table\Table::setDefaultAdapter($this->_db);
    }
    
    protected function tearDown()
    {
        \Zend\Db\Table\Table::setDefaultAdapter(null);
        $this->_service = null;
        $this->_db = null;
    }
    
    public function test_Get_Adpater_Returns_Zend_Auth_Adapter_DbTable()
    {
        $this->assertType('Zend\Authentication\Adapter\DbTable', $this->_service->getAuthAdapter(
                array('email' => '',
                    'passwd' => ''
                )
            )
        );
    }
//
//    public function test_Authenticate_Returns_False_On_Invalid_Credentials()
//    {
//        $mockAdapter = $this->getMock('Zend_Auth_Adapter_DbTable', array(), array(), '', false);
//        $mockAdapter
//            ->expects($this->once())
//            ->method('authenticate')
//            ->will(
//                $this->returnValue(
//                    new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,false)
//                )
//            );
//
//        $this->_service->setAuthAdapter($mockAdapter);
//        $this->assertFalse($this->_service->authenticate(array('email' => 'test@test.com', 'passwd' => 'moo')));
//    }
//
//    public function test_Authenticate_Returns_True_On_Valid_Credentials()
//    {
//        $mockAdapter = $this->getMock('Zend_Auth_Adapter_DbTable', array(), array(), '', false);
//        $mockAdapter
//            ->expects($this->once())
//            ->method('authenticate')
//            ->will(
//                $this->returnValue(
//                    new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,new stdClass())
//                )
//            );
//
//        $this->_service->setAuthAdapter($mockAdapter);
//        $this->assertTrue($this->_service->authenticate(array('email' => 'test@test.com', 'passwd' => 'moo')));
//    }
//
//    public function test_Authenticate_Saves_Identity_To_Session()
//    {
//        $ident = new stdClass();
//        $ident->userId = 1;
//
//        $mockUser = $this->getMock('Storefront_Model_User');
//        $mockUser
//            ->expects($this->once())
//            ->method('getUserByEmail')
//            ->will($this->returnValue(true));
//
//        $mockAdapter = $this->getMock('Zend_Auth_Adapter_DbTable', array(), array(), '', false);
//        $mockAdapter
//            ->expects($this->once())
//            ->method('authenticate')
//            ->will(
//                $this->returnValue(
//                    new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, new stdClass())
//                )
//            );
//
//        $this->_service = new Storefront_Service_Authentication($mockUser);
//        $this->_service->setAuthAdapter($mockAdapter);
//        $this->assertTrue($this->_service->authenticate(array('email' => 'test@test.com', 'passwd' => 'moo')));
//
//        $auth = Zend_Auth::getInstance();
//        $this->assertEquals(true, $auth->getIdentity());
//    }
//
//    public function test_Authenticate_Can_Clear_Identity()
//    {
//        $ident = new stdClass();
//        $ident->userId = 1;
//
//        $mockUser = $this->getMock('Storefront_Model_User');
//        $mockUser
//            ->expects($this->once())
//            ->method('getUserByEmail')
//            ->will($this->returnValue(true));
//
//        $mockAdapter = $this->getMock('Zend_Auth_Adapter_DbTable', array(), array(), '', false);
//        $mockAdapter
//            ->expects($this->once())
//            ->method('authenticate')
//            ->will(
//                $this->returnValue(
//                    new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, new stdClass())
//                )
//            );
//
//        $this->_service = new Storefront_Service_Authentication($mockUser);
//        $this->_service->setAuthAdapter($mockAdapter);
//        $this->assertTrue($this->_service->authenticate(array('email' => 'test@test.com', 'passwd' => 'moo')));
//
//        $auth = Zend_Auth::getInstance();
//
//        $this->assertEquals(true, $auth->getIdentity());
//
//        $this->_service->clear();
//
//        $this->assertFalse($auth->hasIdentity());
//    }
}