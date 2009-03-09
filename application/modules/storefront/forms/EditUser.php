<?php
/**
 * Storefront_Form_EditUser
 *
 * @category   Storefront
 * @package    Storefront_Form
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Form_EditUser extends Storefront_Form_Register
{
    public function init()
    {
        //call the parent init
        parent::init();

        //customize the form
        $this->getElement('email')->removeValidator('UniqueEmail');
        $this->getElement('passwd')->setRequired(false);
        $this->getElement('passwdVerify')->setRequired(false);
        $this->getElement('register')->setLabel('Save User');
    }
}
