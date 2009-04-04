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
    protected $_authService;
    
    public function init()
    {
        // get the default model
        $this->_model = new Storefront_Model_User();
        $this->_authService = new Storefront_Service_Authentication();

        // add forms
        $this->view->registerForm = $this->getRegistrationForm();
        $this->view->loginForm = $this->getLoginForm();
        $this->view->userForm = $this->getUserForm();
    }
    
	public function indexAction() 
	{
        $userID = 1; //will be from session
        $this->view->user = $this->_model->getUserById($userID);
        $this->view->userForm = $this->getUserForm()->populate($this->view->user->toArray());
    }

    public function saveAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('index');
        }

        if (false === ($id = $this->_model->saveUser($request->getPost()))) {
            return $this->render('index');
        }
    }

	public function registerAction()
	{}
	
    public function completeRegistrationAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('register');
        }

        if (false === ($id = $this->_model->registerUser($request->getPost()))) {
            return $this->render('register');
        }
	}
	
	public function listAction()
	{
	    $this->view->users = $this->_model->getUsers();
	}
	
	public function loginAction()
	{}
	
	public function authenticateAction()
	{
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('login');
        }

        // Validate
        $form = $this->_forms['login'];
        if (!$form->isValid($request->getPost())) {
            return $this->render('login');
        }
        
        if (false === $this->_authService->authenticate($form->getValues())) {
            $form->setDescription('Login failed, please try again.');
            return $this->render('login');
        }
        
        $this->_helper->redirector('index');
	}
	
	public function logoutAction()
    {
        $this->_authService->clear();
        $this->_helper->redirector('index');
    }
    
    public function getRegistrationForm()
    {
        $urlHelper = $this->_helper->getHelper('url');
        
        $this->_forms['register'] = $this->_model->getForm('userRegister');
        $this->_forms['register']->setAction($urlHelper->url(array(
            'controller' => 'customer' , 
            'action' => 'complete-registration'
            ), 
            'default'
        ));
        $this->_forms['register']->setMethod('post');
        
        return $this->_forms['register'];
    }

    public function getUserForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['userEdit'] = $this->_model->getForm('userEdit');
        $this->_forms['userEdit']->setAction($urlHelper->url(array(
            'controller' => 'customer' ,
            'action' => 'save'
            ),
            'default'
        ));
        $this->_forms['userEdit']->setMethod('post');

        return $this->_forms['userEdit'];
    }
    
    public function getLoginForm()
    {
        $urlHelper = $this->_helper->getHelper('url');
        
        $this->_forms['login'] = $this->_model->getForm('userLogin');
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
