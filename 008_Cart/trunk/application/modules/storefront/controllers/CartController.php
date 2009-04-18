<?php
/**
 * Cart Controller
 *
 * @category   Storefront
 * @package    Storefront_Controllers
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_CartController extends Zend_Controller_Action
{
    protected $_cartModel;
    protected $_catalogModel;

    public function init()
    {
        $this->_cartModel = new Storefront_Model_Cart();
        $this->_catalogModel = new Storefront_Model_Catalog();
    }

    public function addAction()
    {
        $product = $this->_catalogModel->getProductById($this->_getParam('productId'));

        if (null === $product) {
            throw new SF_Exception('Product could not be added to cart as it does not exist');
        }

        $this->_cartModel->addItem($product, $this->_getParam('qty'));

        $return = rtrim($this->getRequest()->getBaseUrl(), '/') .
            $this->_getParam('returnto');
        $redirector = $this->getHelper('redirector');

        return $redirector->gotoUrl($return);
    }

    public function viewAction()
    {
        $this->view->cartModel = $this->_cartModel;
    }

    public function updateAction()
    {
        foreach($this->_getParam('quantity') as $id => $value) {
            $product = $this->_catalogModel->getProductById($id);
            if (null !== $product) {
                $this->_cartModel->addItem($product, $value);
            }
        }

        /* Should really get from the shippingModel! */
        $this->_cartModel->setShippingCost($this->_getParam('shipping'));

        return $this->_helper->redirector('view');
    }

    public function removeAction(){}
}
