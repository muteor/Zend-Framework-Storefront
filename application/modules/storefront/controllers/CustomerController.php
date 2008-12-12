<?php
/**
 * CustomerController
 * 
 * @category   Storefront
 * @package    Storefront_Controllers
 * @copyright  Copyright (c) 2005-2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_CustomerController extends Zend_Controller_Action 
{
    protected $_model;
    protected $_forms = array();
    protected $_authAdapter;
    
    public function preDispatch()
    {
        // get the default model
        $this->_model = $this->_helper->GetModel('User');
        
        $urlHelper = $this->_helper->getHelper('Url');
        
        $this->_forms['register'] = $this->_helper->GetForm(
            'register',
            array(
                'method' => 'post',
                'action' => $urlHelper->url(
                    array(
                        'controller' => 'customer',
                        'action'     => 'post',
                    )
                )
            ),
            $this->_model
        );
        
        $this->_forms['login'] = $this->_helper->GetForm(
            'login',
            array(
                'method' => 'post',
                'action' => $urlHelper->url(
                    array(
                        'controller' => 'customer',
                        'action'     => 'authenticate',
                    )
                )
            ),
            $this->_model
        );
        
        $this->view->registerForm = $this->_forms['register'];
        $this->view->loginForm = $this->_forms['login'];
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
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('login');
        }

        // Validate
        $form = $this->_forms['login'];
        if (!$form->isValid($request->getPost())) {
            return $this->render('login');
        }
        
        $authService = $this->_helper->getService('authentication');
        if (false === $authService->authenticate($form->getValues())) {
            $form->setDescription('Login failed, please try again.');
            return $this->render('login');
        }
        
        $this->_helper->redirector('index');
	}
	
	public function logoutAction()
    {
        $this->_helper->getService('authentication')->clear();
        $this->_helper->redirector('index');
    }
}
