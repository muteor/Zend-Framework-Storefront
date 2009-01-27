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
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
abstract class SF_Model_Abstract implements SF_Model_Interface 
{   
    /**
     * @var Zend_Loader_PluginLoader
     */
    protected $_loader;
    
    /**
     * @var array Model resource instances
     */
    protected $_instances = array();
    
    /**
     * @var string The path to the model resources
     */
    protected $_resourcePath;
    
    /**
     * @var string The prefix of the model resources
     */
    protected $_resourcePrefix;
    
    /**
     * Construct
     *
     * @param array $options Optional options array
     */
    public function __construct($options = null)
    {
        // inits the module specific defaults (can be overwritten for testing)
        $this->initDefaults();
        
        if (is_array($options)) {
            $this->setOptions($options);
        } elseif ($options instanceof Zend_Config) {
            $this->setConfig($options);
        }
        
        // extensions
        $this->init();
    }
    
    /**
     * Set the options
     *
     * @param array $options
     * @return SF_Model_Abstract
     */
    public function setOptions(array $options)
    {
        if (isset($options['prefix'])) {
            $this->setResourcePrefix($options['prefix']);
            unset($options['prefix']);
        }
        
        if (isset($options['path'])) {
            $this->setResourcePath($options['path']);
            unset($options['path']);
        }
        
        return $this;
    }
    
    /**
     * Set options via Zend_Config
     *
     * @param Zend_Config $config
     * @return SF_Model_Abstract
     */
    public function setConfig(Zend_Config $config)
    {
        return $this->setOptions($config->toArray());
    }
    
    /**
     * Used by extending classes
     */
    public function init()
    {}
    
    /**
     * Set the resource class path
     *
     * @param string $path
     * @return SF_Model_Abstract
     */
    public function setResourcePath($path)
    {
        $this->_resourcePath = $path;
        
        return $this;
    }
    
    /**
     * Set the prefix (used in setOptions)
     *
     * @param string $prefix
     * @return SF_Model_Abstract
     */
    public function setResourcePrefix($prefix)
    {
        $this->_resourcePrefix = $prefix;
        
        return $this;
    }
    
    /**
     * Get the class path for the resources
     *
     * @return string|null
     */
    public function getResourcePath()
    {
        return $this->_resourcePath;
    }
    
    /**
     * Get the class prefix to use when loading resources
     *
     * @return string|null
     */
    public function getResourcePrefix()
    {
        return $this->_resourcePrefix;
    }
	
	/**
	 * Get a resource
	 *
	 * @param string $name
	 * @return SF_Model_Resource_Interface 
	 */
	public function getResource($name) 
	{    
	    return $this->_loadResource($name);
	}
	
	/**
	 * Loads the resource
	 *
	 * @param string $name
	 * @return SF_Model_Resource_Interface
	 */
	protected function _loadResource($name)
	{        
        if(array_key_exists($name, $this->_instances)) {
            return $this->_instances[$name];
        }
        
        $class = $this->getPluginLoader()->load($name);
        $this->_instances[$name] = new $class;
        
        return $this->_instances[$name];
	}
	
	/**
	 * Get the plugin loader
	 *
	 * @return Zend_Loader_PluginLoader
	 */
	public function getPluginLoader()
	{
	    if (null === $this->_loader) {
	        $this->_loader = new Zend_Loader_PluginLoader();
            $this->_loader->addPrefixPath($this->getResourcePrefix(), $this->getResourcePath());
	    }
	    
	    return $this->_loader;
	}
}