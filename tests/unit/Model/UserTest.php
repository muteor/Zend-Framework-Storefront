<?php
namespace SFTest\Model;

use Mockery as m;

/**
 * Test resources to load
 */
require_once __DIR__ . '/TestResources/User.php';

/**
 * Test case for Storefront_User
 * @group migration
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SF_Model_Interface
     */
    protected $_model;

    protected function setUp()
    {
        $options = array(
            'resources' => array(
                'User' => new \SFTest\Model\Resource\UserResource()
            )
        );
        $this->_model = new \Storefront\Model\User($options);
    }

    protected function tearDown()
    {
        $this->_model = null;
        m::close();
    }
    
    public function test_User_Get_User_By_Id_Returns_User_Row()
    {
        $user = $this->_model->getUserById(1);

        $this->assertType('Storefront\\Model\\Resource\\User\\User', $user);
        $this->assertEquals(1, $user->userId);
    }
    
    public function test_User_Get_User_By_Email_Returns_User_Row()
    {
        $user = $this->_model->getUserByEmail('jd5@noemail.com');

        $this->assertType('Storefront\\Model\\Resource\\User\\User', $user);
        $this->assertEquals(5, $user->userId);
    }

    public function test_User_Can_Register()
    {
        $post = array(
            'title'         => 'Mr',
            'firstname'     => 'keith',
            'lastname'      => 'pope',
            'email'         => 'me@me.com',
            'passwd'        => '123456',
            'passwdVerify' => '123456'
        );
        $register = $this->_model->registerUser($post);

        $this->assertEquals(10, $register);
    }

    public function test_User_Register_Fails_On_Invalid_Input()
    {
        $post = array(
            'email'      => 'com',
            'passwd'   => '',
            'passwdVerify' => '123456233'
        );
        $register = $this->_model->registerUser($post);
        $form = $this->_model->getForm('userRegister');

        $this->assertFalse($register);
        foreach ($form->getErrors() as $field => $errors) {
            if ('submit' != $field) {
                if (0 == count($errors)) {
                    $this->fail($field . ' is expected to contain errors');
                }
            }
        }
    }

    public function test_User_Register_Can_Not_Set_Role()
    {
        $post = array(
            'title'         => 'Mr',
            'firstname' => 'keith',
            'lastname' => 'pope',
            'email'      => 'me@me.com',
            'passwd'   => '123456',
            'passwdVerify' => '123456',
            'role'        => 'admin'
        );
        $register = $this->_model->registerUser($post);

        $this->assertEquals(10, $register);
        $rs = $this->_model->getResource('User');
        $inserted = $this->readAttribute($rs, '_rowset');
        $this->assertEquals('Customer', $inserted[10]->role);
    }

    public function test_User_Can_Be_Edited_By_Customer()
    {
        $post = array(
            'userId'     => 10,
            'title'      => 'Mr',
            'firstname' => 'keith',
            'lastname'  => 'pope',
            'email'      => 'me@me.com'
        );

        // Customer
        try {
            $this->_model->setIdentity(array('role' => 'Customer'));
            $edit = $this->_model->saveUser($post);
        } catch (\SF\Acl\AccessDenied $e) {
            $this->fail('Customer should be able to edit user');
        }

        $this->assertEquals(10, $edit);
    }

    public function test_User_Cannot_Be_Edited_By_Guest()
    {
        $post = array(
            'userId'     => 10,
            'title'      => 'Mr',
            'firstname' => 'keith',
            'lastname'  => 'pope',
            'email'      => 'me@me.com'
        );

        // Guest
        try {
            $edit = $this->_model->saveUser($post);
            $this->fail('Guest should not be able to edit user');
        } catch (\SF\Acl\AccessDenied $e) {}
    }

    public function test_User_Can_Be_Edited_By_Admin()
    {
        $post = array(
            'userId'     => 10,
            'title'      => 'Mr',
            'firstname' => 'keith',
            'lastname'  => 'pope',
            'email'      => 'me@me.com'
        );
        
        // Admin
        try {
            $this->_model->setIdentity(array('role' => 'Admin'));
            $edit = $this->_model->saveUser($post);
        } catch (\SF\Acl\AccessDenied $e) {
            $this->fail('Admin should be able to edit user');
        }

        $this->assertEquals(10, $edit);
    }
}