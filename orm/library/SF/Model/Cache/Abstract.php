<?php
/**
 * SF_Model_Cache_Abstract
 *
 * Cache proxy for models, proxies calls to the model to
 * the Zend_Cache class cache.
 *
 * @category   Storefront
 * @package    SF_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
abstract class SF_Model_Cache_Abstract
{
    /**
     * @var array Class methods
     */
    protected $_classMethods;

    /**
     * @var Zend_Cache
     */
    protected $_cache;

    /**
     * @var string Frontend cache type, should be Class
     */
    protected $_frontend;

    /**
     * @var string Backend cache type
     */
    protected $_backend;

    /**
     * @var array Frontend options
     */
    protected $_frontendOptions = array();

    /**
     * @var array Backend options
     */
    protected $_backendOptions = array();

    /**
     * @var SF_Model_Abstract
     */
    protected $_model;

    /**
     * @var string The tag this call will be stored against
     */
    protected $_tagged;

    /**
     * Constructor 
     * 
     * @param SF_Model_Abstract $model
     * @param array|Zend_Config $options 
     */
    public function __construct(SF_Model_Abstract $model, $options, $tagged = null)
    {
        $this->_model = $model;

        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        if (is_array($options)) {
            $this->setOptions($options);
        }

        $this->setTagged($tagged);
    }

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
     * Set the cache instance
     * 
     * @param Zend_Cache $cache 
     */
    public function setCache(Zend_Cache $cache)
    {
        $this->_cache = $cache;
    }

    /**
     * Get the cache instance, configure a new instance 
     * if one not present.
     * 
     * @return Zend_Cache
     */
    public function getCache()
    {
        if (null === $this->_cache) {
            $this->_cache = Zend_Cache::factory(
                $this->_frontend,
                $this->_backend,
                $this->_frontendOptions,
                $this->_backendOptions
            );
        }
        return $this->_cache;
    }

    /**
     * Set the frontend options
     * 
     * @param array $frontend 
     */
    public function setFrontendOptions(array $frontend)
    {
        $this->_frontendOptions = $frontend;
        $this->_frontendOptions['cached_entity'] = $this->_model;
    }

    /**
     * Set the backend options
     * 
     * @param array $backend
     */
    public function setBackendOptions(array $backend)
    {
        $this->_backendOptions = $backend;
    }

    /**
     * Set the backend cache type
     * 
     * @param string $backend 
     */
    public function setBackend($backend)
    {
        $this->_backend = $backend;
    }

    /**
     * Set the frontend cache type
     * 
     * @param string $frontend
     */
    public function setFrontend($frontend)
    {
        if ('Class' != $frontend) {
            throw new SF_Model_Exception('Frontend type must be Class');
        }
        $this->_frontend = $frontend;
    }

    public function setTagged($tagged=null)
    {
        $this->_tagged = $tagged;

        if (null === $tagged) {
            $this->_tagged = 'default';
        }   
    }

    /**
     * Proxy calls from here to Zend_Cache, Zend_Cache
     * will be using the Class frontend which caches the model
     * classes methods.
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        if (!is_callable(array($this->_model, $method))) {
            throw new SF_Model_Exception('Method ' . $method . ' does not exist in class ' . get_class($this->_model) );
        }
        $cache = $this->getCache();
        $cache->setTagsArray(array($this->_tagged));
        $callback = array($cache, $method);
        return call_user_func_array($callback, $params);
    }
}
