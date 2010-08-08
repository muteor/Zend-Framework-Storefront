<?php
/**
 * @namespace SF\Plugin
 */
namespace SF\Plugin;

use Zend\Controller\Plugin\AbstractPlugin as ZendAbstractPlugin,
    Zend\Controller\Request;


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
class AdminContext extends ZendAbstractPlugin
{
    public function preDispatch(Request\AbstractRequest $request)
    {        
        if($request->getParam('isAdmin')) {
            $layout = Zend\Layout\Layout::getMvcInstance();
            $layout->setLayout('admin');
        }
    }
}