<?php
/**
 * TestHelper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

/**
 * User Model
 */
require_once 'modules/storefront/models/User.php';

/**
 * Test case for Storefront_User
 */
class UserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SF_Model_Interface
     */
    protected $_model;
    
    protected function setUp()
    {
        $this->_model = new Storefront_Model_User(array(
            'path'   => dirname(__FILE__) . '/TestResources',
            'prefix' => 'Test',
            )
        );
    }
    
    protected function tearDown()
    {}
    
    public function test_User_Get_User_By_Id_Returns_User_Row()
    {
        $user = $this->_model->getUserById(1);

        $this->assertType('Storefront_Resource_User_Item_Interface', $user);
        $this->assertEquals(1, $user->userId);
    }
    
    public function test_User_Get_User_By_Email_Returns_User_Row()
    {
        $user = $this->_model->getUserByEmail('jd5@noemail.com');

        $this->assertType('Storefront_Resource_User_Item_Interface', $user);
        $this->assertEquals(5, $user->userId);
    }
    
    public function test_User_Save_User_Throws_On_Invalid_Data()
    {
        try {
            $this->_model->saveUser(array());
            $this->fail('SF_Model_Exception expected - no data');
        } catch(SF_Model_Exception $e) {}
        
        try {
            $this->_model->saveUser(array(
                    'title'     => 'Mr',
                    'lastname'  => 'Pope',
                    'email'     => 'keith@noemail.com',
                    'passwd'    => '123456',
                )
            );
            $this->fail('SF_Model_Exception expected - no firstname');
        } catch(SF_Model_Exception $e) {}
        
        try {
            $this->_model->saveUser(array(
                    'title'     => 'Mr',
                    'firstname' => 'Pope',
                    'email'     => 'keith@noemail.com',
                    'passwd'    => '123456',
                )
            );
            $this->fail('SF_Model_Exception expected - no lastname');
        } catch(SF_Model_Exception $e) {}
        
        try {
            $this->_model->saveUser(array(
                    'firstname' => 'Pope',
                    'lastname'  => 'Pope',
                    'email'     => 'keith@noemail.com',
                    'passwd'    => '123456',
                )
            );
            $this->fail('SF_Model_Exception expected - no title');
        } catch(SF_Model_Exception $e) {}
        
        try {
            $this->_model->saveUser(array(
                    'title'     => 'Mr',
                    'firstname' => 'Pope',
                    'lastname'  => 'Pope',
                    'email'     => 'jd1@noemail.com',
                    'passwd'    => '123456',
                )
            );
            $this->fail('SF_Model_Exception expected - email exists');
        } catch(SF_Model_Exception $e) {}
    }
    
    public function test_User_Save_User_Creates_New_User_If_Not_Exists()
    {
        $row = $this->_model->saveUser(array(
                'title'     => 'Mr',
                'firstname' => 'Keith',
                'lastname'  => 'Pope',
                'email'     => 'keith@noemail.com',
                'passwd'    => '123456',
            )
        );
        
        $user = $this->_model->getUserByEmail('keith@noemail.com');
        $this->assertEquals('keith@noemail.com', $user->email);
        $this->assertEquals('Mr', $user->title);
        $this->assertEquals('Keith', $user->firstname);
        $this->assertEquals('Pope', $user->lastname);
        $this->assertEquals(40,strlen($user->passwd));
        $this->assertEquals(32,strlen($user->salt));
    }
}