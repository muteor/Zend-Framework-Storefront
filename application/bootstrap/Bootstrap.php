<?php
/**
 * The application bootstrap used by Zend_Application
 *
 * @category   Bootstrap
 * @package    Bootstrap
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * @var Zend_Log
     */
    protected $_logger;

    /**
     * @var Zend_Application_Module_Autoloader
     */
    protected $_resourceLoader;

    /**
     * @var Zend_Controller_Front
     */
    public $frontController;

    protected function _initPluginLoaderCache()
    {
        // Plugin loader cache
        $classFileIncCache = APPLICATION_PATH . '/../data/pluginLoaderCache.php';
        if (file_exists($classFileIncCache)) {
            include_once $classFileIncCache;
        }
        Zend_Loader_PluginLoader::setIncludeFileCache($classFileIncCache);
    }

    /**
     * Setup request and response so we can use Firebug for logging
     * also make the dispatcher prefix the default module
     */
    protected function _initFrontControllerSettings()
    {
        $this->bootstrap('frontController');
        $this->frontController->setResponse(new Zend_Controller_Response_Http());
        $this->frontController->setRequest(new Zend_Controller_Request_Http());
    }

    /**
     * Setup the logging
     */
    protected function _initLogging()
    {
        $this->bootstrap('frontController');
        $logger = new Zend_Log();

        $writer = 'production' == $this->getEnvironment() ?
			new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../data/logs/app.log') :
			new Zend_Log_Writer_Firebug();
        $logger->addWriter($writer);

		if ('production' == $this->getEnvironment()) {
			$filter = new Zend_Log_Filter_Priority(Zend_Log::CRIT);
			$logger->addFilter($filter);
		}

        $this->_logger = $logger;
        Zend_Registry::set('log', $logger);
    }

    /**
     * Configure the default modules autoloading, here we first create
     * a new module autoloader specifiying the base path and namespace
     * for our default module. This will automatically add the default
     * resource types for us. We also add two custom resources for Services
     * and Model Resources.
     */
    protected function _initDefaultModuleAutoloader()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        
        $this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Storefront',
            'basePath'  => APPLICATION_PATH . '/modules/storefront',
        ));
        $this->_resourceLoader->addResourceTypes(array(
            'modelResource' => array(
              'path'      => 'models/resources',
              'namespace' => 'Resource',
            )
        ));
    }

    /**
     * Setup locale
     */
    protected function _initLocale()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        
        $locale = new Zend_Locale('en_GB');
        Zend_Registry::set('Zend_Locale', $locale);
    }

    /**
     * Setup the database profiling
     */
    protected function _initDbProfiler()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        
        if ('production' !== $this->getEnvironment()) {
            $this->bootstrap('db');
            $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
            $profiler->setEnabled(true);
            $this->getPluginResource('db')->getDbAdapter()->setProfiler($profiler);
        }
    }
    
    /**
     * Add the config to the registry
     */
    protected function _initConfig()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        Zend_Registry::set('config', $this->getOptions());
    }

    /**
     * Setup the view
     */
    protected function _initView()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->init();

        $this->_view = $viewRenderer->view;

        // add global helpers
        $this->_view->addHelperPath(APPLICATION_PATH . '/views/helpers', 'Zend_View_Helper');

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
            'layoutPath' => APPLICATION_PATH . '/layouts/scripts'
            )
        );
    }

    /**
     * Add required routes to the router
     */
    protected function _initRoutes()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        $this->bootstrap('frontController');

        $router = $this->frontController->getRouter();

        // Admin context route
        $route = new Zend_Controller_Router_Route(
            'admin/:module/:controller/:action/*',
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
                'module'        => 'storefront',
                'categoryIdent' => '',
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
                'categoryIdent' => '',
                'page'          => 1
            ),
            array(
                'categoryIdent' => '[a-zA-Z-_0-9]+',
                'page'          => '\d+'
            )
        );

        $router->addRoute('catalog_category', $route);
    }

    /**
     * Add Controller Action Helpers
     */
    protected function _initActionHelpers()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        Zend_Controller_Action_HelperBroker::addHelper(new SF_Controller_Helper_Acl());
        Zend_Controller_Action_HelperBroker::addHelper(new SF_Controller_Helper_RedirectCommon());
        Zend_Controller_Action_HelperBroker::addHelper(new SF_Controller_Helper_Service());
    }

    protected function _initDbCaches()
    {
        // Metadata cache for Zend_Db_Table
        $frontendOptions = array(
            'automatic_serialization' => true
        );

        $cache = Zend_Cache::factory('Core',
            'Apc',
            $frontendOptions
        );
        Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);

        // Paginator cache
        $paginator = Zend_Paginator::factory(array());
        $frontendOptions = array(
            'lifetime' => 3600,
            'automatic_serialization' => true
        );
        $backendOptions = array(
            'cache_dir'=> APPLICATION_PATH . '/../data/cache/db'
        );

        $cache = Zend_cache::factory('Core',
            'File',
            $frontendOptions,
            $backendOptions
        );
        Zend_Paginator::setCache($cache);
    }
}
