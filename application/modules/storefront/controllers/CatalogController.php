<?php
class Storefront_CatalogController extends Zend_Controller_Action
{
    protected $_catalogModel;
    
    public function init()
    {
        $this->_catalogModel = $this->_helper->getModel('Catalog');
    }

    public function indexAction()
    {
        $this->view->showQueries = true;
        
        $products = $this->_catalogModel->getProductsByCategory(
            $this->_getParam('categoryIdent', 0)
        );
        
        foreach ($products as $product) {
            echo $product->productId . '<br />';
        }
    }
    
    public function viewAction()
    {}
    
    public function adminAction()
    {}
}