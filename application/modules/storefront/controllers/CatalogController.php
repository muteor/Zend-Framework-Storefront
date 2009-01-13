<?php
class Storefront_CatalogController extends Zend_Controller_Action
{
    protected $_catalogModel;
    
    public function init()
    {
        $this->_catalogModel = $this->_helper->getModel('Catalog');
    }

    public function indexAction()
    {}
    
    public function adminAction()
    {}
}