<?
class Bootstrap extends Zend_Application_Bootstrap_Base
{
    protected $_logger;
    protected $_front;
    protected $_config;
    public $frontController;

    public function _initFrontController()
    {
        $this->bootstrapLogging();
        $this->_logger->info('Bootstrap ' . __METHOD__);
        
        $this->_front = Zend_Controller_Front::getInstance();
        $this->frontController = $this->_front;
        if ('development' === $this->getEnvironment()) {
            $this->_front->throwExceptions(true);
        }
        return $this;
    }

    public function _initLogging()
    {
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

        return $this;
    }

    protected function _initConfig()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);

        $this->_config = new Zend_Config_Ini(APPLICATION_PATH . '/config/store.ini', $this->getEnvironment(), true);
        Zend_Registry::set('config', $this->_config);

        return $this;
    }

    protected function _initDb()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);

        $config = $this->_config;
        if (!isset($config->db)) {
            return $this;
        }

        $db = Zend_Db::factory($config->db);

        if ($this->getEnvironment() == 'development') {
            $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
            $profiler->setEnabled(true);
            $db->setProfiler($profiler);
        }

        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        Zend_Registry::set('db', $db);

        // Setup metadata cache
//        $frontendOptions = array(
//            'automatic_serialization' => true
//        );
//
//        $backendOptions  = array(
//            'cache_dir' => APPLICATION_PATH . '/../data/cache/meta'
//        );
//
//        $cache = Zend_Cache::factory('Core',
//                                     'File',
//                                     $frontendOptions,
//                                     $backendOptions);
//
//        if ('production' === $this->getEnvironment()) {
//            Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
//        }

        return $this;
    }

    public function _initControllers()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        
        $this->bootstrapFrontController();
        $this->_front->getDispatcher()->setParam('prefixDefaultModule', true);
        $this->_front->addModuleDirectory(APPLICATION_PATH . '/modules');
        $this->_front->setDefaultModule('storefront');
    }

    public function _initView()
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
            'layoutPath' => APPLICATION_PATH . '/layouts/scripts'
            )
        );

        return $this;
    }

    /**
     * Register additional plugins, only ones that use
     * hooks after routeStartup though!
     */
    public function _initFrontPlugins()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);

        $this->bootstrapFrontController();
        $this->_front->registerPlugin( new SF_Plugin_Action());
        $this->_front->registerPlugin( new SF_Plugin_AdminContext());

        return $this;
    }

    /**
     * Add required routes to the router
     */
    public function _initRoutes()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        $this->bootstrapFrontController();

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

    public function run()
    {
        $this->_front->dispatch();
    }
}