<?php
class Storefront_IndexController extends Zend_Controller_Action
{
    public function init()
    {}

    public function indexAction()
    {
        $p = $this->_helper->getModel('Catalog')->getProductById(100);
        $p2 = $this->_helper->getModel('Catalog')->getProductByIdent('Product-2');

        $ps = $this->_helper->getModel('Catalog')->getProductsByCategory(3, array(2,1), array('name'));
        
        $user = $this->_helper->getModel('User')->getUserByEmail('keith.pope@allpay.net');
        
        print_r($user);
        
        $this->view->headTitle('Product1', 'PREPEND');
    }
}