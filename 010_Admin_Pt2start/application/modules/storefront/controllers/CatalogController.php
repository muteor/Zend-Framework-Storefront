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
    
    public function listAction()
    {
        if (!$this->_helper->acl('Admin')) {
            return $this->_helper->redirectCommon('gotoLogin');
        }
        
        $this->view->categorySelect = $this->_catalogModel->getForm('catalogCategorySelect');
        $this->view->categorySelect->populate($this->getRequest()->getPost());
        $this->view->categoryId = $this->_getParam('categoryId');

        if ($this->_getParam('categoryId')) {
            $this->view->products = $this->_catalogModel->getProductsByCategory(
                (int) $this->_getParam('categoryId'),
                null,
                null,
                false
            );
        }
    }

    public function addcategoryAction()
    {
        if (!$this->_helper->acl('Admin')) {
            return $this->_helper->redirectCommon('gotoLogin');
        }

        $this->view->categoryForm = $this->_getCategoryForm();
    }

    public function savecategoryAction()
    {
        if (!$this->_helper->acl('Admin')) {
            return $this->_helper->redirectCommon('gotoLogin');
        }
        
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('addcategory');
        }

        if(false === $this->_catalogModel->saveCategory($request->getPost())) {
            $this->view->categoryForm = $this->_getCategoryForm();
            return $this->render('addcategory');
        }

        $redirector = $this->getHelper('redirector');
        return $redirector->gotoRoute(array('action' => 'list'), 'admin');
    }

    public function productimagesAction()
    {
        if (!$this->_helper->acl('Admin')) {
            return $this->_helper->redirectCommon('gotoLogin');
        }

        if (false === ($id = $this->_getParam('id',false))) {
            throw new SF_Exception('No product id sent');
        }

        $product = $this->_catalogModel->getProductById($id);

        if (null === $product) {
            throw new SF_Exception('Unknown product');
        }

        $this->view->product = $product;
        $this->view->imageForm = $this->_getProductImageForm();
        $this->view->imageForm->populate($product->toArray());
    }

    public function saveimageAction()
    {
        if (!$this->_helper->acl('Admin')) {
            return $this->_helper->redirectCommon('gotoLogin');
        }

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('addproduct');
        }

        if (false === ($id = $this->_getParam('productId',false))) {
            throw new SF_Exception('No product id sent');
        }

        $product = $this->_catalogModel->getProductById($id);

        if(false === ($id = $this->_catalogModel->saveProductImage($product, $request->getPost()))) {
            $this->view->product = $product;
            $this->view->imageForm = $this->_getProductImageForm();
            $this->view->imageForm->populate($product->toArray());
            return $this->render('productimages');
        }
        
        $redirector = $this->getHelper('redirector');
        return $redirector->gotoRoute(array('action' => 'productimages', 'id' => $product->productId), 'admin');
    }

    public function deleteproductAction()
    {
        if (false === ($id = $this->_getParam('id',false))) {
            throw new SF_Exception('Unknown product');
        }

        $this->_catalogModel->deleteProduct($id);
        
        $redirector = $this->getHelper('redirector');
        return $redirector->gotoRoute(array('action' => 'list'), 'admin');
    }
    
    public function getBreadcrumb($category)
    {
        $this->view->bread = $this->_catalogModel->getParentCategories($category);
    }

    protected function _getCategoryForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['addCategory'] = $this->_catalogModel->getForm('catalogCategoryAdd');
        $this->_forms['addCategory']->setAction($urlHelper->url(array(
            'controller' => 'catalog' ,
            'action' => 'savecategory'
            ),
            'admin'
        ));
        $this->_forms['addCategory']->setMethod('post');

        return $this->_forms['addCategory'];
    }

    protected function _getProductImageForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['addImage'] = $this->_catalogModel->getForm('catalogProductImageAdd');
        $this->_forms['addImage']->setAction($urlHelper->url(array(
            'controller' => 'catalog' ,
            'action' => 'saveimage'
            ),
            'admin'
        ));
        $this->_forms['addImage']->setMethod('post');

        return $this->_forms['addImage'];
    }
}