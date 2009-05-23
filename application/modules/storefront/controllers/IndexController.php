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
    }
}