<?php
/**
 * Yadif
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to tsmckelvey@gmail.com so I can send you a copy immediately.
 */

require_once 'Exception.php';

/**
 * Yadif_Container
 *
 * @category   Yadif
 * @copyright  Copyright (c) 2008 Thomas McKelvey
 * @copyright  Copyright (c) 2009 Benjamin Eberlei
 * @author     Thomas McKelvey (http://github.com/tsmckelvey/yadif/tree/master)
 * @author     Benjamin Eberlei (http://github.com/beberlei/yadif/tree/master)
 */
class Yadif_Container
{
    const CHAR_CONFIG_VALUE = '%';
    const CHAR_ARGUMENT     = ':';

    /**
     * Identifier for singleton scope
     */
    const SCOPE_SINGLETON = "singleton";

    /**
     * Identifier for prototype scope (new object each call)
     */
    const SCOPE_PROTOTYPE = "prototype";

    /**
     * Class index key of component $config
     */
    const CONFIG_CLASS = 'class';

    /**
     * Arguments index key of component $config
     */
    const CONFIG_ARGUMENTS = 'arguments';

    /**
     * Parameters
     */
    const CONFIG_PARAMETERS = 'params';

    /**
     * Singleton index key of component $config
     */
    const CONFIG_SCOPE = 'scope';

    /**
     * Setter Methods to call config index key
     */
    const CONFIG_METHODS = 'methods';

    /**
     * Method Name
     */
    const CONFIG_METHOD = 'method';

    /**
     * Factory Config key for classes that are instantiated via a static factory method
     */
    const CONFIG_FACTORY = 'factory';

    /**
     * container of component configurations
     *
     * @var array
     */
    protected $_container = array();

    /**
     * parameters which have been set, expected to be bound
     *
     * @var array
     */
    protected $_parameters = array();

    /**
     * All managed instances inside this container which are not Scoped "Prototype"
     *
     * @var array
     */
    protected $_instances = array();

    /**
     * Config
     *
     * @var Zend_Config
     */
    protected $_config = null;

    /**
     * Construct Dependency Injection Container
     *
     * @param Zend_Config|array $components
     * @param Zend_Config       $config
     */
    public function __construct($components = array(), Zend_Config $config=null)
    {
        $this->addComponents($components);
        $this->setConfig($config);
    }

    /**
     * Set Config object
     *
     * @param  Zend_Config $config
     * @return Yadif_Container
     */
    public function setConfig(Zend_Config $config=null)
    {
        $this->_config = $config;
        return $this;
    }

    /**
     * Getter method for internal array of component configurations
     *
     * @return array
     */
    public function getContainer()
    {
        return $this->_container;
    }

    /**
     * Getter method for internal array of parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * Get currently managed instances of the container.
     *
     * In its current implementations only the singleton scoped objects are returned,
     * but in an implementation with additional session scope it is necessary to return
     * also those from this function.
     *
     * @return array
     */
    public function getInstances()
    {
        return $this->_instances;
    }

    /**
     * Get Config inside this Container
     *
     * @return Zend_Config|null
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Merge two Containers
     *
     * @todo   Handle duplicates, currently array_merge overwrites them
     * @param  Yadif_Container $container
     * @return Yadif_Container
     */
    public function merge(Yadif_Container $container)
    {
        $this->_container = array_merge($this->_container, $container->getContainer());
        $this->_instances = array_merge($this->_instances, $container->getInstances());

        $otherConfig = $container->getConfig();
        $ownConfig   = $this->getConfig();
        if($otherConfig instanceof Zend_Config) {
            if($ownConfig == null) {
                $this->setConfig($otherConfig);
            } else {
                if($ownConfig->readOnly() == true) {
                    $this->setConfig(new Zend_Config(array_merge($ownConfig->toArray(), $otherConfig->toArray())));
                } else {
                    $this->setConfig($ownConfig->merge($otherConfig));
                }
            }
        }

        return $this;
    }

    /**
     * Add several components to the container via a config.
     *
     * @param array|Zend_Config $components
     * @return Yadif_Container
     */
    public function addComponents($components)
    {
        if($components instanceof Zend_Config) {
            $components = $components->toArray();
        }

        if (isset($components) && is_array($components)) {
            foreach ($components as $componentName => $componentConfig) {
                $this->addComponent( $componentName, $componentConfig );
            }
        }
    }

    /**
     * Add a component to the container
     *
     * If $config is omitted, $name is assumed to be the classname.
     *
     * @param string|Yadif_Container $name Class-tag, class, or Yadif_Container
     * @param array $config Array of configuration values
     * @return Yadif_Container
     */
    public function addComponent($name = null, array $config = null)
    {
        if ($name instanceof Yadif_Container) { // if Yadif_Container
            $this->merge($name);
        } elseif(is_string($name)) {
            if (!is_array($config) || !isset($config[self::CONFIG_CLASS])) { // assume name is the class name
                $config[self::CONFIG_CLASS] = $name;
            }

            if (!isset($config[self::CONFIG_ARGUMENTS]) || !is_array($config[self::CONFIG_ARGUMENTS])) {
                $config[ self::CONFIG_ARGUMENTS ] = array();
            }

            if(!isset($config[self::CONFIG_PARAMETERS])) {
                $config[self::CONFIG_PARAMETERS] = array();
            }

            // if class is set and doesn't exist
            if (!class_exists( $config[self::CONFIG_CLASS] )) {
                throw new Yadif_Exception( 'Class ' . $config[self::CONFIG_CLASS] . ' not found' );
            }

            // check for singleton config parameter and set it to true as default if not found.
            if(!isset($config[self::CONFIG_SCOPE])) {
                $config[self::CONFIG_SCOPE] = self::SCOPE_SINGLETON;
            }

            if(!isset($config[self::CONFIG_METHODS])) {
                $config[self::CONFIG_METHODS] = array();
            } elseif(!is_array($config[self::CONFIG_METHODS])) {
                throw new Yadif_Exception("Methods of component '".$name."' are not in array format.");
            } else {
                foreach($config[self::CONFIG_METHODS] AS $k => $method) {
                    if(!isset($method[self::CONFIG_METHOD])) {
                        throw new Yadif_Exception("No method name was set for injection via method.");
                    }
                    if(!isset($method[self::CONFIG_PARAMETERS])) {
                        $method[self::CONFIG_PARAMETERS] = array();
                    }
                    if(!isset($method[self::CONFIG_ARGUMENTS])) {
                        $method[self::CONFIG_ARGUMENTS] = array();
                    } elseif(!is_array($method[self::CONFIG_ARGUMENTS])) {
                        throw new Yadif_Exception("Arguments of method '".$method[self::CONFIG_METHOD]."' in component ".$name." are not given as an array.");
                    }

                    $config[self::CONFIG_METHODS][$k] = $method;
                }
            }

            $name = strtolower($name);
            $this->_container[$name] = $config;
        } else {
            throw new Yadif_Exception('$string not string|Yadif_Container, is ' . gettype($name));
        }

        return $this;
    }

    /**
     * Bind a parameter
     *
     * @param string $param The parameter name, to be given with a leading colon ":param"
     * @param mixed $value The value to bind to the parameter
     * @return Yadif_Container
     */
    public function bindParam($param, $value)
    {
        if (!is_string($param)) {
            throw new Yadif_Exception('$param not string, is ' . gettype($param));
        }

        if ($param[0] != ':') {
            throw new Yadif_Exception($param . ' must start with a colon (:)');
        }

        $this->_parameters[$param] = $value;

        return $this;
    }

    /**
     * Retrieve a parameter by name
     *
     * @param  mixed $param Retrieve named parameter
     * @param  string $component
     * @param  string $method
     * @return mixed
     */
    public function getParam($param, $component=null)
    {
        $component = strtolower($component);
        if(isset($this->_container[$component])) {
            $component = $this->_container[$component];
            if(isset($component[self::CONFIG_PARAMETERS][$param])) {
                return $component[self::CONFIG_PARAMETERS][$param];
            }
        }

        if(isset($this->_parameters[$param])) {
            return $this->_parameters[$param];
        } else {
            return null;
        }
    }

    /**
     * Bind multiple parameters by way of array
     * @param array $params Array of parameters, key as param to bind, value as the bound value
     * @return Yadif_Container
     */
    public function bindParams($params = null)
    {
        if (!is_array($params)) {
            throw new Yadif_Exception('$params must be array, is ' . gettype($params));
        }

        foreach ($params as $param => $value) {
            $this->bindParam($param, $value);
        }

        return $this;
    }

    /**
     * Get several components at once.
     *
     * @param  array $components
     * @return array
     */
    public function getComponents(array $components)
    {
        foreach ($components as $k =>$value) {
            $components[$k] = $this->getComponent($value);
        }
        return $components;
    }

    protected function injectParameters($arguments, $component, array $params=array())
    {
        $injection = array();
        foreach($arguments AS $k => $argument) {
            if(is_array($argument)) {
                $value = $this->injectParameters($argument, $component);
            } elseif(substr($argument, 0, 1) == self::CHAR_CONFIG_VALUE && substr($argument, -1) == self::CHAR_CONFIG_VALUE) {
                $value = $this->getConfigValue($argument);
            } elseif(substr($argument, 0, 1) == self::CHAR_ARGUMENT) {
                if(isset($params[$argument])) {
                    $value =  $params[$argument];
                } else {
                    $value =  $this->getParam($argument, $component);
                }
            } else {
                $value = $this->getComponent($argument);
            }
            $injection[$k] = $value;
        }
        return $injection;
    }

    /**
     * Get back a fully assembled component based on the configuration provided beforehand
     *
     * @param  string $name The name of the component
     * @return mixed
     */
    public function getComponent($name)
    {
        if (!is_string($name)) {
            return $name;
        }

        $origName = $name;
        $name = strtolower($name);

        if(isset($this->_instances[$name])) {
            return $this->_instances[$name];
        }

        if($name === "thiscontainer") {
            return $this;
        } elseif($name === "clonecontainer") {
            return clone $this;
        } elseif(!array_key_exists($name, $this->_container)) {
            throw new Yadif_Exception("Component '".$origName."' does not exist in container.");
        }

        $component = $this->_container[$name];
        $scope = $component[self::CONFIG_SCOPE];

        $componentReflection = new ReflectionClass($component[ self::CONFIG_CLASS ]);

        $constructorArguments = $component[self::CONFIG_ARGUMENTS];
        $setterMethods        = $component[self::CONFIG_METHODS];

        if(isset($component[self::CONFIG_FACTORY])) {
            if(!is_callable($component[self::CONFIG_FACTORY])) {
                throw new Yadif_Exception("No valid callback given for the factory method of '".$origName."'.");
            }
            $component = call_user_func_array($component[self::CONFIG_FACTORY], $this->injectParameters($constructorArguments, $name));
        } else if(empty($constructorArguments)) { // if no instructions
                $component = $componentReflection->newInstance();
            } else {
                $component = $componentReflection->newInstanceArgs($this->injectParameters($constructorArguments, $name));
            }

        foreach ($setterMethods as $method) {
            $methodName = $method[self::CONFIG_METHOD];
            $params = $method[self::CONFIG_PARAMETERS];

            $injection = array();
            if(isset($method[self::CONFIG_ARGUMENTS])) {
                $argsName = $method[self::CONFIG_ARGUMENTS];
                $injection = $this->injectParameters($argsName, null, $params);
            }

            if ($componentReflection->getMethod($methodName)->isConstructor()) {
                throw new Yadif_Exception("Cannot use constructor in 'methods' setter injection list. Use 'arguments' key instead.");
            } else {
                call_user_func_array(array($component, $methodName), $injection);
            }
        }

        if($scope !== self::SCOPE_PROTOTYPE) {
            $this->_instances[$name] = $component;
        }

        return $component;
    }

    /**
     * Magic Get accesses the {@link getComponent} function and returns the object.
     *
     * @param  string $component
     * @throws Yadif_Exception
     * @return object
     */
    public function __get($name)
    {
        return $this->getComponent($name);
    }

    /**
     * Call allows to initiate requests to get[ComponetName] and links against {@link getComponent}.
     *
     * @param  string $method
     * @param  array  $arguments
     * @throws Yadif_Exception
     * @return object
     */
    public function __call($method, $args)
    {
        if(substr($method, 0, 3) !== "get") {
            throw new Yadif_Exception("Container __call only intercepts get[ComponetName]() like calls.");
        }
        $component = substr($method, 3);
        return $this->getComponent($component);
    }

    /**
     * Set instance of a name.
     *
     * @throws Yadif_Exception
     * @param string $name
     * @param object $instance
     */
    public function __set($name, $instance)
    {
        $origName = $name;
        $name = strtolower($name);
        if(is_object($instance)) {
            $this->_instances[$name] = $instance;
        } else {
            throw new Yadif_Container("Setting instance '".$origName."' with non-object not possible.");
        }
    }

    /**
     * Is there an instance or a component registered with the given name?
     *
     * @param  string $name
     * @return boolean
     */
    public function __isset($name)
    {
        $name = strtolower($name);
        return (
        isset($this->_instances[$name]) ||
            isset($this->_container[$name])
        );
    }

    /**
     * Given an accessor specification %config.foo.bar% traverse the config and return value.
     *
     * @param  string $accessor
     * @return mixed
     */
    protected function getConfigValue($accessor)
    {
        if($this->_config === null) {
            throw new Yadif_Exception("A config value '".$accessor."' is required but no config was given!");
        }

        $accessor = substr($accessor, 1, strlen($accessor)-2);

        $parts = explode(".", $accessor);
        $current = $this->_config;
        for($i = 0; $i < count($parts); $i++) {
            $current = $current->$parts[$i];
        }
        return $current;
    }
}
