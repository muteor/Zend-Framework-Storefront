<?php
class Storefront_IndexController extends Zend_Controller_Action
{   
    public function init()
    {}

    public function indexAction()
    {
        if ($service = $this->_helper->service('page', 'cms')) {
            $this->view->page = $service->getPageById(1);
        }

        $c = $this->getInvokeArg('bootstrap')->getContainer();

        $m = $c->getStorefront_Model_Catalog();

        print_r($m);
    }
}