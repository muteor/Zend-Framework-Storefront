<?php
/**
 * Acl action helper used for when we want to control access to resources
 * that do not have a Model.
 *
 * @category   Storefront
 * @package    SF_Controller_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SF_Controller_Helper_Acl extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Acl
     */
    protected $_acl;

    /**
     * Init the acl instance for this module - all modules must have an acl!
     */
    public function init()
    {
        $module = $this->getRequest()->getModuleName();
        $acl = ucfirst($module) . '_Model_Acl_' . ucfirst($module);
        $this->_acl = new $acl;
    }

    public function isAllowed($role, $resource=null, $privilege=null)
    {
        return $this->_acl->isAllowed($role, $resource, $privilege);
    }

    public function direct($role, $resource=null, $privilege=null)
    {
        return $this->isAllowed($role, $resource, $privilege);
    }
}