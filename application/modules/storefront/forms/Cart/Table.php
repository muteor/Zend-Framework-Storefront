<?php
/**
 * Base of the cart table where users edit delete from their cart,
 * elements are dynamically added for the qty fields in
 * Zend_View_Helper_Cart::cartTable
 *
 * @see Zend_View_Helper_Cart::cartTable
 * @category   Storefront
 * @package    Storefront_Form
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Form_Cart_Table extends Zend_Form
{
    public function init()
    {
        $this->setDisableLoadDefaultDecorators(true);

        $this->setDecorators(array(
            'Form'
        ));

        $this->setMethod('post');
        $this->setAction('');

        $this->addElement('submit', 'update-cart', array(
            'decorators' => array(
                'ViewHelper'
            ),
            'label' => 'Update'
        ));
    }
}
