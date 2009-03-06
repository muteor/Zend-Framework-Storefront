<?php
class Storefront_Bootstrap extends Zend_Application_Module_Bootstrap
{
    public function _initModule()
    {
        $this->getResourceLoader()
               ->addResourceType(
                    'modelResource',
                    'models/resources',
                    'Resource'
               );
         $this->getResourceLoader()
               ->addResourceType(
                    'service',
                    'services',
                    'Service'
               );
    }

    public function run(){}
}
