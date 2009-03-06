<?php
/**
 * CustomerController
 * 
 * @category   Storefront
 * @package    Storefront_Controllers
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_CustomerController extends Zend_Controller_Action 
{
    protected $_model;
    protected $_forms = array();
    protected $_authAdapter;
    
    public function init()
    {
        // get the default model
        $this->_model = new Storefront_Model_User();
        
        // add forms
        $this->view->registerForm = $this->getRegistrationForm();
        $this->view->loginForm = $this->getLoginForm();
    }
    
	public function indexAction() 
	{
	}

	public function registerAction()
	{ 
        
	}
	
    public function postAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('register');
        }

        // validate form
        $form = $this->_forms['register'];
        if (!$form->isValid($request->getPost())) {
            return $this->render('register');
        }
        
	    // Valid form
        $id = $this->_model->saveUser($form->getValues());
        
	}
	
	public function listAction()
	{
	    $this->view->users = $this->_model->getUsers();
	}
	
	public function loginAction()
	{}
	
	public function authenticateAction()
	{
		$this->view->showQueries = true;
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('login');
        }

        // Validate
        $form = $this->_forms['login'];
        if (!$form->isValid($request->getPost())) {
            return $this->render('login');
        }
        
        $authService = new Storefront_Service_Authentication();
        if (false === $authService->authenticate($form->getValues())) {
            $form->setDescription('Login failed, please try again.');
            return $this->render('login');
        }
        
        $this->_helper->redirector('index');
	}
	
	public function logoutAction()
    {
        $authService = new Storefront_Service_Authentication();
        $authService->clear();
        $this->_helper->redirector('index');
    }
    
    public function getRegistrationForm()
    {
        $urlHelper = $this->_helper->getHelper('url');
        
        $this->_forms['register'] = new Storefront_Form_Register();
        $this->_forms['register']->setAction($urlHelper->url(array(
            'controller' => 'customer' , 
            'action' => 'post'
            ), 
            'default'
        ));
        $this->_forms['register']->setMethod('post');
        
        return $this->_forms['register'];
    }
    
    public function getLoginForm()
    {
        $urlHelper = $this->_helper->getHelper('url');
        
        $this->_forms['login'] = new Storefront_Form_Login();
        $this->_forms['login']->setAction($urlHelper->url(array(
            'controller' => 'customer',
            'action'     => 'authenticate',
            ), 
            'default'
        ));
        $this->_forms['login']->setMethod('post');
        
        return $this->_forms['login'];
    }
}
