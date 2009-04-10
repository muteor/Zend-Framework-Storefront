<?php
/**
 * AdminController
 * 
 * @category   Storefront
 * @package    Storefront_Controllers
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_AdminController extends Zend_Controller_Action 
{
	/**
	 * The default action - show the home page
	 */
	public function indexAction() 
	{

        if ($this->_helper->acl('Guest', 'Admin')) {
            echo 'Yes';
        } else {
            echo 'No';
        }

        if ($this->_helper->acl('Customer', 'Admin')) {
            echo 'Yes';
        } else {
            echo 'No';
        }

        if ($this->_helper->acl('Admin', 'Admin', null)) {
            echo 'Yes';
        } else {
            echo 'No';
        }
	}
}
