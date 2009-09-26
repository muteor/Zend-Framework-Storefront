<?php
class Cms_Objects extends Yadif_Module
{
    protected function configure()
    {
        $this->_initModelResources();
        $this->_initModels();
        $this->_initServices();
    }

    protected function _initModels()
    {
        $this->bind("Cms_Model_Page")
             ->to("Cms_Model_Page")
                ->method('addResource')
                    ->args(':name','Cms_Resource_Page')
                    ->param(':name', 'Page')
             ->scope(Yadif_Container::SCOPE_PROTOTYPE);
    }

    protected function _initModelResources()
    {
        $this->bind('Cms_Resource_Page')
             ->to('Cms_Resource_Page');
    }

    protected function _initServices()
    {
        $this->bind('Cms_Service_Page')
             ->to('Cms_Service_Page')
                ->method('setModel')
                    ->args('Cms_Model_Page');
    }
}