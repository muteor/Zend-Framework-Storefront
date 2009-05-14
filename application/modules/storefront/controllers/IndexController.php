<?php
class Storefront_IndexController extends Zend_Controller_Action
{
    protected $_pageModel;
    
    public function init()
    {
        if (class_exists('Cms_Model_page')) {
            $this->_pageModel = new Cms_Model_Page();
        }
    }

    public function indexAction()
    {
        if (null !== $this->_pageModel) {
            $this->view->pageData = $this->_pageModel->getPageById(1)->body;
        }
    }
}