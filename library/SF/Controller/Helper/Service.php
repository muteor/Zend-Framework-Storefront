<?php
/**
 * @namespace SF\Controller\Helper
 */
namespace SF\Controller\Helper;

use Zend\Controller\Action\Helper\AbstractHelper as ZendHelperAbstract;

/**
 * Module service finder
 *
 * @category   Storefront
 * @package    SF\Controller\Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Service extends ZendHelperAbstract
{
    protected $_services = array();

    public function getService($service, $module)
    {
        if (!isset($this->_services[$module][$service])) {
            $class = implode('_', array(
                    ucfirst($module),
                    'Service',
                    ucfirst($service)
            ));

            $front = Zend_Controller_Front::getInstance();
            $classPath = $front->getModuleDirectory($module) . '/services/' . ucfirst($service) . '.php';
            if (!file_exists($classPath)) {
                return false;
            }
            if (!class_exists($class)) {
                throw new SF_Exception("Class $class not found in " . basename($classPath));
            }
            $this->_services[$module][$service] = new $class();
        }
	    return $this->_services[$module][$service];
    }

    public function direct($service, $module)
    {
        return $this->getService($service, $module);
    }
}