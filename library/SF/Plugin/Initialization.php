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
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
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
     * @var Zend_Log
     */
    protected $_logger;
    
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
        $this->_initLogging();
        $this->_initHelpers();
        $this->_configure();
        $this->_initDb();
        $this->_initView();
        $this->_registerFrontPlugins();
        $this->_initRoutes();

    }
    
    protected function _initLogging()
    {
        $logger = new Zend_Log();
		
        $writer = 'production' == $this->_env ? 
			new Zend_Log_Writer_Stream($this->_root . '/data/logs/app.log') : 
			new Zend_Log_Writer_Firebug();
        $logger->addWriter($writer);
        
		if ('production' == $this->_env) {
			$filter = new Zend_Log_Filter_Priority(Zend_Log::CRIT);
			$logger->addFilter($filter);
		}
		
        $this->_logger = $logger;
        Zend_Registry::set('log', $logger);
		
        return $this;
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
     * Get the config
     * 
     * @return Zend_Config_Ini
     */
    protected function _getConfig()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        
        if (null === self::$_config) {
            self::$_config = new Zend_Config_Ini($this->_root . '/application/config/store.ini', $this->_env, true);
            self::$_config->root = $this->_root;
        }
        return self::$_config;
    }
    
    /**
     * Setup the default db adapter
     * 
     * @return SF_Plugin_Initialization This instance for chaining calls
     */
    protected function _initDb()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        
        $config = $this->_getConfig();
        if (!isset($config->db)) {
            return $this;
        }

        $db = Zend_Db::factory($config->db);
        
        if ($this->_env == 'development') {
            $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
            $profiler->setEnabled(true);
            $db->setProfiler($profiler);
        }
        
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        Zend_Registry::set('db', $db);
        
        // Setup metadata cache
        $frontendOptions = array(
            'automatic_serialization' => true
        );
        
        $backendOptions  = array(
            'cache_dir' => $this->_root . '/data/cache/meta'
        );
        
        $cache = Zend_Cache::factory('Core',
                                     'File',
                                     $frontendOptions,
                                     $backendOptions);
        
        if ('production' === $this->_env) {
            Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
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
     * Add the global action helpers
     * @return SF_Plugin_Initialization This instance for chaining calls
     */
    public function _initHelpers()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        
        Zend_Controller_Action_HelperBroker::addPath($this->_root . '/library/SF/Controller/helpers', 'SF_Helper');
        return $this;
    }

    /**
     * Configure the application
     * 
     * @return SF_Plugin_Initialization This instance for chaining calls
     */
    protected function _configure()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        
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
        $this->_logger->info('Bootstrap ' . __METHOD__);
        
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
     * Register additional plugins, only ones that use
     * hooks after routeStartup though!
     */
    protected function _registerFrontPlugins()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        
        $this->_front->registerPlugin( new SF_Plugin_Action());
        $this->_front->registerPlugin( new SF_Plugin_AdminContext());
    }
    
    /**
     * Add required routes to the router
     */
    protected function _initRoutes()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        
        $router = $this->_front->getRouter();

        // Admin context route
        $route = new Zend_Controller_Router_Route(
            'admin/:module/:controller/:action',
            array(
                'action'     => 'index',
                'controller' => 'admin',
                'module'     => 'storefront',
                'isAdmin'    => true
            )
        );
        
        $router->addRoute('admin', $route);
        
        // catalog category product route
        $route = new Zend_Controller_Router_Route(
            'catalog/:categoryIdent/:productIdent',
            array(
                'action'        => 'view',
                'controller'    => 'catalog',
                'module'        => 'storefront'
            ),
            array(
                'categoryIdent' => '[a-zA-Z-_0-9]+',
                'productIdent'  => '[a-zA-Z-_0-9]+'
            )
        );
        
        $router->addRoute('catalog_category_product', $route);
        
        // catalog category route
        $route = new Zend_Controller_Router_Route(
            'catalog/:categoryIdent/:page',
            array(
                'action'        => 'index',
                'controller'    => 'catalog',
                'module'        => 'storefront',
                'page'          => 1
            ),
            array(
                'categoryIdent' => '[a-zA-Z-_0-9]+',
                'page'          => '\d+'
            )
        );
        
        $router->addRoute('catalog_category', $route);
    }
}