<?php
/**
 * @namespace SF\Model
 */
namespace SF\Model;

use SF\Model\Cache,
    Zend\Filter;

/**
 * AbstractModel
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
abstract class AbstractModel implements Model
{
    /**
    * @var array Class methods
    */
    protected $_classMethods;
    
    /**
     * @var array Model resource instances
     */
    protected $_resources = array();

    /**
     * @var array Form instances
     */
    protected $_forms = array();

    /**
     * @var SF_Model_Cache_Abstract
     */
    protected $_cache;

    /**
     * @var array cache options
     */
    protected $_cacheOptions = array();

   /**
    * Constructor
    *
    * @param array|Zend_Config|null $options
    * @return void
    */
    public function __construct($options = null)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        if (is_array($options)) {
            $this->setOptions($options);
        }

        $this->init();
    }

    /**
     * Constructor extensions
     */
    public function init()
    {}

   /**
    * Set options using setter methods
    *
    * @param array $options
    * @return SF_Model_Abstract 
    */
    public function setOptions(array $options)
    {
        if (null === $this->_classMethods) {
            $this->_classMethods = get_class_methods($this);
        }
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $this->_classMethods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * Get a resource
     *
     * @param string $name
     * @return SF_Model_Resource_Interface
     */
    public function getResource($name)
    {
        if (!isset($this->_resources[$name])) {
            $class = $this->_getNamespace() . '\\Resource\\' . ucfirst($name) . 'Resource';
            $this->_resources[$name] = new $class();
        }
        return $this->_resources[$name];
    }

    /**
     * Injector for resources
     * 
     * @param array $resources
     */
    public function setResources(array $resources)
    {
        $this->_resources = $resources;
    }

    /**
     * Get a Form
     * 
     * @param string $name
     * @return Zend_Form 
     */
    public function getForm($name)
    {
        if (!isset($this->_forms[$name])) {
            $class = $this->_getNamespace() . '\\Form\\' . ucfirst($name);
            $class = str_replace('Model\\', '', $class);
            $this->_forms[$name] = new $class(array('model' => $this));
        }
	    return $this->_forms[$name];
    }

    /**
     * Set the cache to use
     * 
     * @param SF_Model_Cache_Abstract $cache
     */
    public function setCache(Cache\AbstractCache $cache)
    {
        $this->_cache = $cache;
    }

    /**
     * Set the cache options
     * 
     * @param array $options 
     */
    public function setCacheOptions(array $options)
    {
        $this->_cacheOptions = $options;
    }

    /**
     * Get the cache options
     * 
     * @return array
     */
    public function getCacheOptions()
    {
        if (empty($this->_cacheOptions)) {
            $frontendOptions = array(
                'lifetime' => 1800,
                'automatic_serialization' => true
            );
            $backendOptions = array(
                'cache_dir'=> APPLICATION_PATH . '/../data/cache/db'
            );
            $this->_cacheOptions = array(
                'frontend'        => 'Class',
                'backend'         => 'File',
                'frontendOptions' => $frontendOptions,
                'backendOptions'  => $backendOptions
            );
        }
        return $this->_cacheOptions;
    }

    /**
     * Query the cache
     *
     * @param string $tagged The tag to save data to
     * @return SF_Model_Cache_Abstract 
     */
    public function getCached($tagged = null)
    {
        if (null === $this->_cache) {
            $this->_cache = new Cache\Cache($this, $this->getCacheOptions(), $tagged);
        }
        $this->_cache->setTagged($tagged);
        return $this->_cache;
    }

    /**
     * Classes are named spaced using their module name
     * this returns that module name or the first class name segment.
     *
     * @return string This class namespace
     */
    private function _getNamespace()
    {
        $reflection = new \ReflectionClass($this);
        return $reflection->getNamespaceName();
    }
}
