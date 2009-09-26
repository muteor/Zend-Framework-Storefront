<?php
class Cms_Objects extends Yadif_Module
{
    protected function configure()
    {
        $this->_initModelResources();
        $this->_initModelAcl();
        $this->_initCache();
        $this->_initModels();
    }

    protected function _initModels()
    {
        $this->bind("Storefront_Model_Catalog")
             ->to("Storefront_Model_Catalog")
                ->param('name', 'MOOOOOOO')
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
    }

    protected function _initModelResources()
    {
        $this->bind('Storefront_Resource_Category')
             ->to('Storefront_Resource_Category');

        $this->bind('Storefront_Resource_Product')
             ->to('Storefront_Resource_Product');

        $this->bind('Storefront_Resource_Productimage')
             ->to('Storefront_Resource_Productimage');
    }

    protected function _initModelAcl()
    {
        $this->bind('Storefront_Model_Acl_Storefront')
             ->to('Storefront_Model_Acl_Storefront');
    }

    protected function _initCache()
    {
        $this->bind('Storefront_Model_Cache_Catalog')
             ->to('SF_Model_Cache')
                ->args('Storefront_Model_Catalog', '%storefront.model.cache.options%');
    }
}