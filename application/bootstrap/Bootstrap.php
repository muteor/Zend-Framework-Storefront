<?
class Bootstrap extends Zend_Application_Bootstrap_Base
   {
       public function _initFrontController()
       {
           $this->front = Zend_Controller_Front::getInstance();
       }


       public function _initControllers()
       {
           die('XXX');
           // runs _initFrontController() if not already run:
           $this->bootstrapFrontController();

           $this->front->addModuleDirectory(MODULE_PATH . '/modules');
       }

       public function _initRequest()
       {
           // runs _initFrontController() if not already run:
           $this->bootstrapFrontController();

           $this->request = new Zend_Controller_Request_Http;
           $this->front->setRequest($this->request);
       }

       public function run()
       {
           $this->front->dispatch();
       }
    }