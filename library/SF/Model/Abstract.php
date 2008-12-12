<?php
/** SF_Model_Interface */
require_once 'SF/Model/Interface.php';

/** Zend_Registry */
require_once 'SF/Model/Resource/Registry.php';

/**
 * SF_Model_Abstract
 * 
 * Base model class that all our models will inherit from, modules
 * need to make their own base class so they can apply module specific 
 * settings.
 * 
 * The main purpose of the base model is to provide models with a way
 * of handling their resources and class loading functionality.
 * 
 * @category   Storefront
 * @package    SF_Model
 * @copyright  Copyright (c) 2005-2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
abstract class SF_Model_Abstract implements SF_Model_Interface 
{   
    /**
     * @var Zend_Loader_PluginLoader
     */
    protected $_loader;
	
	/**
	 * Add a resource that will stored for later retrieval by
	 * the model.
	 * 
	 * @see SF_Model_Interface::addResource()
	 * 
	 * @param string  $name      The name of the resource class to add
	 * @param boolean $isDefault Is this the default resource 
	 * @param boolean $lock      Can this resource be overwritten
	 * @param string  $className The resource class name if different from $name
	 * @return SF_Model_Abstract Used to chain methods
	 */
	public function addResource($name, $isDefault=false, $lock=false, $className=null) 
	{	    
	    $name = $this->_formatName($name);
	    
	    $ident = get_class($this) . '_' . $name;
	    
	    if (SF_Model_Resource_Registry::isRegistered($ident) && SF_Model_Resource_Registry::get($ident)->isLocked()) {
	        return $this;
	    }
	    
	    if (null === $className) {
	        $className = $name;
	    }
	    
	    if (true === $isDefault) {
	        $registry = SF_Model_Resource_Registry::getInstance();
            
            foreach ($registry as $key => $container) {
                if (($container instanceof SF_Model_Resource_Registry_Container) 
                    && $container->isDefault() && $container->inNamespace(get_class($this))) {
                    $container->isDefault = false;
                }
            }           
	    }
	    
	    $container = new SF_Model_Resource_Registry_Container($ident, $className, $isDefault, $lock);
	    
	    SF_Model_Resource_Registry::set($ident, $container);
	    
	    return $this;
	}
	
	/**
	 * Get a resource
	 * 
	 * @see SF_Model_Interface::getResource()
	 * @param string $name Optional The resource to get or null for default
	 * @return SF_Model_Resource_Interface
	 */
	public function getResource($name=null) 
	{    
	    $resource = null;
	    
	    if(null === $name) {
	        $registry = SF_Model_Resource_Registry::getInstance();
	        
	        foreach ($registry as $key => $container) {
	            if (($container instanceof SF_Model_Resource_Registry_Container) 
	               && $container->isDefault() && $container->inNamespace(get_class($this))) {
	                $resource = $this->_loadResource($container->className);
	            }
	        }	        
	        return $resource;
	    }
	    
	    $name = get_class($this) . '_' . $this->_formatName($name);
	    
	    if(SF_Model_Resource_Registry::isRegistered($name)) {
	        $container = SF_Model_Resource_Registry::get($name);
	        $resource = $this->_loadResource($container->className);
	    }
	    
	    if(null === $resource) {
	        throw new SF_Model_Exception("Resource $name is not registered");
	    }
	    
	    return $resource;
	}
	
	/**
	 * Load the resource class and instantiate it.
	 * 
	 * @param $name The name of the resource to load
	 * return SF_Model_Resource_Interface
	 */
	abstract protected function _loadResource($name);
	
	/**
	 * Get the plugin loader and configure it.
	 */
	abstract public function getPluginLoader();
	
	/**
	 * Format the name of the resource to load
	 * 
	 * @param string The unformatted string
	 * @return string The formatted string
	 */
    protected function _formatName($unformatted)
    {
        if(null == $unformatted) {
            throw new SF_Model_Exception('Resource name must not be null');
        }
        
        $segments = explode('_', $unformatted);

        foreach ($segments as $key => $segment) {
            $segment        = str_replace(array('-', '.'), ' ', strtolower($segment));
            $segment        = preg_replace('/[^a-z0-9 ]/', '', $segment);
            $segments[$key] = str_replace(' ', '', ucwords($segment));
        }

        return implode('_', $segments);
    }
}