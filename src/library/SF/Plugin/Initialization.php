<?php
/**
 * Application Initialization Plugin
 * 
 * Plugin is registered with the Front Controllers plugin broker and 
 * extends the routeStartup. The plugin contains all the applications
 * initialization code.
 * 
 * @category   Storefront
 * @package    SF_Plugin
 * @copyright  Copyright (c) 2005-2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SF_Plugin_Initialization extends Zend_Controller_Plugin_Abstract 
{
    /**
     * @var string Runtime environment
     */
    protected $_env;
    
    /**
     * @var Zend_Controller_Front
     */
    protected $_front;
    
    /**
     * @var string Path to app root
     */
    protected $_root;
    
    /**
     * @var Zend_View View instance
     */
    protected $_view;
    
    /**
     * @var Zend_Config
     */
    protected static $_config;
    
    /**
     * Constructor
     * 
     * @param string $env    The runtime environment
     * @param string $root   Optional path to application root
     */
    public function __construct($env = 'production', $root = null)
    {
        $this->_setEnv($env)
             ->_setRoot($root)
             ->_initPHPEnv();
        
        $this->_front = Zend_Controller_Front::getInstance();
    }
    
    /**
     * Start application initialization using the routeStartup hook.
     * 
     * This is called before the Front Controller starts to evaluate 
     * routes during the dispatch process. We do this as it is the 
     * earliest hook in the Front Controllers dispatch process.
     * 
     * @see Zend_Controller_Plugin_Abstract 
     * @param Zend_Controller_Request_Abstract $request    The request object
     */
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $this->_initModules();
        $this->_configure();
        $this->_initView();
        $this->_initDb();
    }
    
    /**
     * Set the application root directory
     * 
     * If root is null try to set it automatically 
     *
     * @param string $root Path to the application root
     * @return SF_Plugin_Initialization This instance for chaining calls
     */
    protected function _setRoot($root)
    {
        if (null === $root) { 
            $root = realpath(dirname(__FILE__) . '/../../../');
        }
        
        $this->_root = $root;
        
        Zend_Registry::set('root', $this->_root);
        
        return $this;
    }
    
    /**
     * Set the application environment type
     * 
     * Are we in a development or production environment? 
     *
     * @param string $env The environment
     * @return SF_Plugin_Initialization This instance for chaining calls
     */
    protected function _setEnv($env)
    {
        $this->_env = $env;
        
        return $this;
    }

    /**
     * Set the PHP Environment variables 
     * 
     * @return SF_Plugin_Initialization This instance for chaining calls
     */
    protected function _initPHPEnv()
    {
        //set timezone for Zend_Date
        date_default_timezone_set('Europe/London');
        
        if( $this->_env == 'development' || $this->_env == 'test') {
            ini_set('display_errors',true);
            error_reporting(E_ALL|E_STRICT);
        }
        
        return $this;
    }
    
    /**
     * Setup the modules and realted settings.
     * 
     * @return SF_Plugin_Initialization This instance for chaining calls
     */
    protected function _initModules()
    {
        $this->_front->getDispatcher()->setParam('prefixDefaultModule', true);
        $this->_front->addModuleDirectory($this->_root . '/application/modules');
        $this->_front->setDefaultModule('storefront');
        
        
        return $this;
    }

    /**
     * Configure the application
     * 
     * @return SF_Plugin_Initialization This instance for chaining calls
     */
    protected function _configure()
    {
        if($this->_env == 'development' || $this->_env == 'test') {
            $this->_front->throwExceptions(true);
        }
        
        return $this;
    }
    
    /**
     * Initialize view related settings.
     * 
     * @return SF_Plugin_Initialization This instance for chaining calls
     */
    protected function _initView()
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->init();
        
        $this->_view = $viewRenderer->view;
        
        // set encoding and doctype
        $this->_view->setEncoding('UTF-8');
        $this->_view->doctype('XHTML1_STRICT');
        
        // set the content type and language
        $this->_view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        $this->_view->headMeta()->appendHttpEquiv('Content-Language', 'en-US');
        
        // set css links and a special import for the accessibility styles
        $this->_view->headStyle()->setStyle('@import "/css/access.css";');
        $this->_view->headLink()->appendStylesheet('/css/reset.css');
        $this->_view->headLink()->appendStylesheet('/css/main.css');
        $this->_view->headLink()->appendStylesheet('/css/form.css');
        
        // setting the site in the title
        $this->_view->headTitle('Storefront');
        
        // setting a separator string for segments:
        $this->_view->headTitle()->setSeparator(' - ');
        
        // init the layouts
        Zend_Layout::startMvc(array('layout' => 'main',
            'layoutPath' => $this->_root . '/application/layouts/scripts'
            )
        );
        
        return $this;
    }
    
    /**
     * Setup the default db adapter
     * 
     * @return SF_Plugin_Initialization This instance for chaining calls
     */
    protected function _initDb()
    {
        $config = $this->_getConfig();
        if (!isset($config->db)) {
            return $this;
        }

        $db = Zend_Db::factory($config->db);                     
        
        return $this;
    }
    
    /**
     * Get the config
     * 
     * @return Zend_Config_Ini
     */
    protected function _getConfig()
    {
        if (null === self::$_config) {
            self::$_config = new Zend_Config_Ini(
                $this->_root .
                    '/application/config/store.ini', 
                $this->_env, 
                true
            );
            self::$_config->root = $this->_root;
        }
        return self::$_config;
    }
}