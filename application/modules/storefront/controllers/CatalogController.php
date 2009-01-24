<?php
class Storefront_CatalogController extends Zend_Controller_Action
{
    /**
     * @var Storefront_Catalog
     */
    protected $_catalogModel;
    
    public function init()
    {
        $this->_catalogModel = $this->_helper->resourceLoader->getModel('Catalog');
    }

    public function indexAction()
    {       
        $products = $this->_catalogModel->getProductsByCategory(
            $this->_getParam('categoryIdent', 0),
			$this->_getParam('page', 1)
        );

        $category = $this->_catalogModel->getCategoryByIdent($this->_getParam('categoryIdent', ''));
        
        if (null === $category) {
            $this->getResponse()->setException(new Zend_Controller_Action_Exception('',404));
            $this->_forward('error','error');
            return;
        }
        
        $subs = $this->_catalogModel->getCategories($category->categoryId);
        $bread = $this->_catalogModel->getParentCategories($category);

        $this->view->assign(array(
            'category' => $category,
            'subCategories' => $subs,
            'products' => $products,
            'bread' => $bread,
            )
        );
    }
    
    public function viewAction()
    {}
    
    public function adminAction()
    {}
}