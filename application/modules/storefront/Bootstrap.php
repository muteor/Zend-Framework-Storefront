<?php
namespace Storefront;

use Zend\Application\Bootstrap as ZendBootstrap,
    Zend\Loader\PluginLoader as ZendPluginLoader,
    Zend\Log as ZendLog,
    Zend\Registry as ZendRegistry,
    Zend\Locale\Locale as ZendLocale,
    Zend\Db\Profiler as ZendDbProfiler,
    Zend\Controller\Router\Route as ZendRouter,
    Zend\Controller\Action\HelperBroker as ZendActionHelperBroker,
    Zend\Search as ZendSearch,
    SF\Controller\Helper as SFActionHelper
;

/**
 * The application bootstrap used by Zend\Application
 *
 * @category   Bootstrap
 * @package    Bootstrap
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Bootstrap extends ZendBootstrap
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
     * Configure the pluginloader cache
     */
    protected function _initPluginLoaderCache()
    {
        if ('production' == $this->getEnvironment()) {
            $classFileIncCache = APPLICATION_PATH . '/../data/cache/pluginLoaderCache.php';
            if (file_exists($classFileIncCache)) {
                include_once $classFileIncCache;
            }
            ZendPluginLoader::setIncludeFileCache($classFileIncCache);
        }
    }

    /**
     * Setup the logging
     */
    protected function _initLogging()
    {
        $this->bootstrap('frontController');
        
        $logger = new ZendLog\Logger();

        $writer = 'production' == $this->getEnvironment() ?
			new ZendLog\Writer\Stream(APPLICATION_PATH . '/../data/logs/app.log') :
			new ZendLog\Writer\Firebug();
        $logger->addWriter($writer);

        if ('production' == $this->getEnvironment()) {
                $filter = new ZendLog\Filter\Priority(Zend_Log::CRIT);
                $logger->addFilter($filter);
        }

        $this->_logger = $logger;
        ZendRegistry::set('log', $logger);
    }

    /**
     * Configure the resource autoloader
     */
    protected function _initConfigureResourceAutoloader()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);

        $this->getResourceLoader()->addResourceTypes(array(
            'modelResource' => array(
              'path'      => 'models/resources',
              'namespace' => 'Model\\Resource',
            ),
            'document' => array(
                'path' => 'models/document',
                'namespace' => 'Model\\Document'
            )
        ));
    }

    /**
     * Setup locale
     */
    protected function _initLocale()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        
        $locale = new ZendLocale('en_GB');
        ZendRegistry::set('Zend_Locale', $locale);
    }

    /**
     * Setup the database profiling
     */
    protected function _initDbProfiler()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);

        if ('production' !== $this->getEnvironment()) {
            $this->bootstrap('db');
            $profiler = new ZendDbProfiler\Firebug('All DB Queries');
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
        ZendRegistry::set('config', $this->getOptions());
    }

    /**
     * Setup the view
     */
    protected function _initViewSettings()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);

        $this->bootstrap('view');

        $this->_view = $this->getResource('view');

        // add global helpers
        $this->_view->addHelperPath(APPLICATION_PATH . '/views/helpers', 'Zend\View\Helper');

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

        // workaround for the default route being renamed to application
        $route = new ZendRouter\Module(array(
            'action'     => 'index',
            'controller' => 'index',
            'module'     => 'storefront',
        ));
        $router->addRoute('default', $route);

        // Admin context route
        $route = new ZendRouter\Route(
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
        $route = new ZendRouter\Route(
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
        $route = new ZendRouter\Route(
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
        ZendActionHelperBroker::addHelper(new SFActionHelper\Acl());
        ZendActionHelperBroker::addHelper(new SFActionHelper\RedirectCommon());
        ZendActionHelperBroker::addHelper(new SFActionHelper\Service());
    }

    /**
     * Init the db metadata and paginator caches
     */
    protected function _initDbCaches()
    {
        $this->_logger->info('Bootstrap ' . __METHOD__);
        if ('production' == $this->getEnvironment()) {
            // Metadata cache for Zend_Db_Table
            $frontendOptions = array(
                'automatic_serialization' => true
            );

            $cache = Zend_Cache::factory('Core',
                'Apc',
                $frontendOptions
            );
            Zend\Db\Table\AbstractTable::setDefaultMetadataCache($cache);
        }
    }

    /**
     * Add gracefull error handling to the bootstrap process
     */
    protected function _bootstrap($resource = null)
    {
        $errorHandling = $this->getOption('errorhandling');
        try {
            parent::_bootstrap($resource);
        } catch(Exception $e) {
            if (true == (bool) $errorHandling['graceful']) {
                $this->__handleErrors($e, $errorHandling['email']);
            } else {
                throw $e;
            }
        }
    }

    /**
     * Add graceful error handling to the dispatch, this will handle
     * errors during Front Controller dispatch.
     */
    public function run()
    {
        $errorHandling = $this->getOption('errorhandling');
        try {
            parent::run();
        } catch(Exception $e) {
            if (true == (bool) $errorHandling['graceful']) {
                $this->__handleErrors($e, $errorHandling['email']);
            } else {
                throw $e;
            }
        }
    }

    /**
     * Handle errors gracefully, this will work as long as the views,
     * and the Zend classes are available
     *
     * @param Exception $e
     * @param string $email
     */
    protected function __handleErrors(Exception $e, $email)
    {
        header('HTTP/1.1 500 Internal Server Error');
        $view = new Zend_View();
        $view->addScriptPath(dirname(__FILE__) . '/../views/scripts');
        echo $view->render('fatalError.phtml');

        if ('' != $email) {
            $mail = new Zend_Mail();
            $mail->setSubject('Fatal error in application Storefront');
            $mail->addTo($email);
            $mail->setBodyText(
                $e->getFile() . "\n" .
                $e->getMessage() . "\n" .
                $e->getTraceAsString() . "\n"
            );
            @$mail->send();
        }
    }

    protected function _initZendSearch()
    {
        $filter = new ZendSearch\Lucene\Analysis\TokenFilter\ShortWords();

        $analyzer = new ZendSearch\Lucene\Analysis\Analyzer\Common\Utf8Num\CaseInsensitive();
        $analyzer->addFilter($filter);
        
        ZendSearch\Lucene\Analysis\Analyzer\Analyzer::setDefault($analyzer);
    }
}
