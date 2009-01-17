<?php
class Storefront_IndexController extends Zend_Controller_Action
{
    public function init()
    {}

    public function indexAction()
    {        
        $this->view->headTitle('Welcome', 'PREPEND'); 
    }
}