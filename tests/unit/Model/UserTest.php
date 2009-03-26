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
        _SF_Autloader_SetUp();

        // configure the resource loader atuo load models
        $loader = new Zend_Loader_Autoloader_Resource(array(
            'basePath' => APPLICATION_PATH . '/modules/storefront',
            'namespace' => 'Storefront'
            )
        );
        $loader->addResourceType('Model', 'models', 'Model');
        $loader->addResourceType('Form', 'forms', 'Form');

        // configure another loader so we can replace Model Resources, forms
        $loader = new Zend_Loader_Autoloader_Resource(array(
            'basePath' => dirname(__FILE__),
            'namespace' => 'Storefront'
            )
        );
        $loader->addResourceType('modelResource', 'TestResources', 'Resource');

        $this->_model = new Storefront_Model_User();
    }

    protected function tearDown()
    {
        _SF_Autloader_TearDown();
        $this->_model = null;
    }
    
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

    public function test_User_Can_Register()
    {
        $post = array(
            'title'         => 'Mr',
            'firstname' => 'keith',
            'lastname' => 'pope',
            'email'      => 'me@me.com',
            'passwd'   => '123456',
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

    public function test_User_Can_Be_Edited()
    {
        $post = array(
            'userId'     => 10,
            'title'         => 'Mr',
            'firstname' => 'keith',
            'lastname' => 'pope',
            'email'      => 'me@me.com'
        );
        $edit = $this->_model->saveUser($post);

        $this->assertEquals(10, $edit);
    }
}