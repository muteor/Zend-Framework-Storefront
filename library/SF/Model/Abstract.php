<?php
/** SF_Model_Interface */
require_once 'SF/Model/Interface.php';

/**
 * SF_Model_Abstract
 * 
 * Base model class that all our models will inherit from.
 * 
 * The main purpose of the base model is to provide models with a way
 * of handling their resources.
 * 
 * @category   Storefront
 * @package    SF_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
abstract class SF_Model_Abstract implements SF_Model_Interface 
{      
    /**
     * @var array Model resource instances
     */
    protected $_instances = array();
	
	/**
	 * Get a resource
	 *
	 * @param string $name
	 * @return SF_Model_Resource_Interface 
	 */
	public function getResource($name) 
	{
        if (!isset($this->_instances[$name])) {
            $current = explode('_', get_class($this));
            $class = join('_', array($current[0], 'Resource', ucfirst($name)));
            $this->_instances[$name] = new $class();
        }
	    return $this->_instances[$name];
	}
}