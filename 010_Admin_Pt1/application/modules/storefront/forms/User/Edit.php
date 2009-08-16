<?php
/**
 * Storefront_Form_Register
 *
 * The registration form
 *
 * @category   Storefront
 * @package    Storefront_Form
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Form_User_Edit extends Storefront_Form_User_Base
{
    public function init()
    {
        //call the parent init
        parent::init();

        //customize the form
        $this->getElement('passwd')->setRequired(false);
        $this->getElement('passwdVerify')->setRequired(false);
        $this->getElement('submit')->setLabel('Save User');
        $this->removeElement('role');
    }
}