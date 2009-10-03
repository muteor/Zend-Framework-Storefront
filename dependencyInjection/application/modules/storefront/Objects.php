<?php
class Storefront_Objects extends Yadif_Module
{
    protected function configure()
    {
        $this->_initModelResources();
        $this->_initModelAcl();
        $this->_initCache();
        $this->_initModels();
        $this->_initServices();
    }

    protected function _initModels()
    {
        $this->bind('Storefront_Model_Catalog')
             ->to('Storefront_Model_Catalog')
                ->method('addResource')
                    ->args(':name','Storefront_Resource_Category')
                    ->param(':name', 'Category')
                ->method('addResource')
                    ->args(':name', 'Storefront_Resource_Product')
                    ->param(':name', 'Product')
                ->method('addResource')
                    ->args(':name', 'Storefront_Resource_Productimage')
                    ->param(':name', 'Productimage')
                ->method('setAcl')
                    ->args('Storefront_Model_Acl_Storefront')
                ->method('setCache')
                    ->args('Storefront_Model_Cache_Catalog')
             ->scope(Yadif_Container::SCOPE_PROTOTYPE);

        $this->bind('Storefront_Model_Cart');

        $this->bind('Storefront_Model_User')
             ->to('Storefront_Model_User')
                ->method('addResource')
                    ->args(':name', 'Storefront_Resource_User')
                    ->param(':name', 'User');
    }

    protected function _initModelResources()
    {
        $this->bind('Storefront_Resource_Category');
        $this->bind('Storefront_Resource_Product');
        $this->bind('Storefront_Resource_Productimage');
        $this->bind('Storefront_Resource_User');
    }

    protected function _initModelAcl()
    {
        $this->bind('Storefront_Model_Acl_Storefront');
    }

    protected function _initCache()
    {
        $this->bind('Storefront_Model_Cache_Catalog')
             ->to('SF_Model_Cache')
                ->args('%storefront.model.cache.options%');
    }

    protected function _initServices()
    {
        $this->bind('Storefront_Service_Authentication')
             ->args('Storefront_Model_User');
    }
}