<?php
/**
 * The registration form
 * 
 * @category   Storefront
 * @package    Storefront_Form
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Form_User_Register extends Storefront_Form_User_Base
{
    public function init()
    {
        // make sure parent is called!
        parent::init();

        // specialize this form
        $this->removeElement('userId');
        $this->getElement('submit')->setLabel('Register');
        $this->removeElement('role');
    }
}
