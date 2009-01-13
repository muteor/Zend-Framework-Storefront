<?php
/**
 * SF_Plugin_AdminContext
 * 
 * This plugin detects if we are in the admininstration area
 * and changes the layout to the admin template.
 * 
 * This relies on the admin route found in the initialization plugin
 *
 * @category   Storefront
 * @package    SF_Plugin
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SF_Plugin_AdminContext extends Zend_Controller_Plugin_Abstract 
{
    public function preDispatch(Zend_Controller_Request_Abstract $request) 
    {        
        if($request->getParam('isAdmin')) {
            $layout = Zend_Layout::getMvcInstance();
            $layout->setLayout('admin');
        }
    }
}