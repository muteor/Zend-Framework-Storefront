<?php
class Storefront_CatalogController extends Zend_Controller_Action
{
    /**
     * @var Storefront_Model_Catalog
     */
    protected $_catalogModel;

    /**
     * @var array
     */
    protected $_forms = array();
    
    public function init()
    {
        $this->_catalogModel = new Storefront_Model_Catalog();
    }

    public function indexAction()
    {       
        $products = $this->_catalogModel->getProductsByCategory(
            $this->_getParam('categoryIdent', 0),
			$this->_getParam('page', 1),
            array('name')
        );

        $category = $this->_catalogModel->getCategoryByIdent($this->_getParam('categoryIdent', ''));
        
        if (null === $category) {
            throw new SF_Exception_404('Unknown category ' . $this->_getParam('categoryIdent'));
        }
        
        $subs = $this->_catalogModel->getCategoriesByParentId($category->categoryId);
        $this->getBreadcrumb($category);

        $this->view->assign(array(
            'category' => $category,
            'subCategories' => $subs,
            'products' => $products
            )
        );
    }
    
    public function viewAction()
    {
        $product = $this->_catalogModel->getProductByIdent($this->_getParam('productIdent', 0));
        
        if (null === $product) {
            throw new SF_Exception_404('Unknown product ' . $this->_getParam('productIdent'));
        }
        
        $category = $this->_catalogModel->getCategoryByIdent($this->_getParam('categoryIdent', ''));
        $this->getBreadcrumb($category);
        
        $this->view->assign(array(
            'product' => $product,
            )
        );
    }
    
    public function getBreadcrumb($category)
    {
        $this->view->bread = $this->_catalogModel->getParentCategories($category);
    }
}