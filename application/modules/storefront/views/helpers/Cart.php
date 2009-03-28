<?php
/**
 * Zend_View_Helper_ProductImage
 *
 * Helper for displaying product images
 *
 * @category   Storefront
 * @package    Storefront_View_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Zend_View_Helper_Cart extends Zend_View_Helper_Abstract
{
    public $cartModel;

    public function Cart()
    {
        $this->cartModel = new Storefront_Model_Cart();

        return $this;
    }

    public function getSummary()
    {
        $currency = new Zend_Currency();
        $itemCount = count($this->cartModel);

        if (0 == $itemCount) {
            return '<p>No Items</p>';
        }

        $html  = '<p>Items: ' . $itemCount;
        $html .= ' | Total: '.$currency->toCurrency($this->cartModel->getSubTotal());
        $html .= '<br /><a href="';
        $html .= $this->view->url(array(
            'controller' => 'cart', 
            'action' => 'view',
            'module' => 'storefront'
            ),
            'default',
            true
        );
        $html .= '">View Cart</a></p>';

        return $html;
    }

    public function addForm(Storefront_Resource_Product_Item $product)
    {
        $form = $this->cartModel->getForm('cartAdd');
        $form->populate(array(
            'productId' => $product->productId,
            'returnto' => $this->view->url()
        ));
        $form->setAction($this->view->url(array(
            'controller' => 'cart',
            'action' => 'add',
            'module' => 'storefront'
            ),
            'default',
            true
        ));
        return $form;
    }
}
