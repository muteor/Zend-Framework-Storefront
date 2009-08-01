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
            ),
            'service' => array(
              'path'      => 'services',
              'namespace' => 'Service',
            ),
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
    protected function _initViewSettings()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);

        $this->bootstrap('view');

        $this->_view = $this->getResource('view');

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
    }
}
