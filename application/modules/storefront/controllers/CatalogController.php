<?php
class Storefront_CatalogController extends Zend_Controller_Action
{
    /**
     * @var Storefront_Catalog
     */
    protected $_catalogModel;
    
    public function init()
    {
        $this->_catalogModel = $this->_helper->getModel('Catalog');
    }

    public function indexAction()
    {       
        $products = $this->_catalogModel->getProductsByCategory(
            $this->_getParam('categoryIdent', 0),
			$this->_getParam('page', 1)
        );

        $category = $this->_catalogModel->getCategoryByIdent($this->_getParam('categoryIdent', ''));
        
        $this->view->assign(array(
            'category' => $category, 
            'products' => $products
            )
        );
    }
    
    public function viewAction()
    {}
    
    public function adminAction()
    {}
}