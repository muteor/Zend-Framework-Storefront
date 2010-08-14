<?php
namespace Storefront\Form\Cart;

use SF\Form;

/**
 * Add to cart form
 *
 * @category   Storefront
 * @package    Storefront_Form
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Add extends Form\AbstractForm
{
    public function init()
    {
        $this->setDisableLoadDefaultDecorators(true);

        $this->setMethod('post');
        $this->setAction('');

        $this->setDecorators(array(
            'FormElements',
            'Form'
        ));
        
        $this->addElement('text', 'qty', array(
            'decorators' => array(
                'ViewHelper'
            ),
            'style' => 'width: 20px;',
            'value' => 1
        ));

        $this->addElement('submit', 'buy-item', array(
            'decorators' => array(
                'ViewHelper'
            ),
            'label' => 'Add to cart'
        ));

        $this->addElement('hidden', 'productId', array(
            'decorators' => array(
                'ViewHelper'
            ),
        ));
        $this->addElement('hidden', 'returnto', array(
            'decorators' => array(
                'ViewHelper'
            ),
        ));
    }
}
