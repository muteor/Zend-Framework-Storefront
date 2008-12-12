<?php
/**
 * Storefront_Form_Register
 * 
 * The registration form
 * 
 * @category   Storefront
 * @package    Storefront_Form
 * @copyright  Copyright (c) 2005-2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Form_Register extends SF_Form_Abstract 
{
    public function init()
    {
        // add path to custom validators
        $this->addElementPrefixPath(
            'Storefront_Validate', 
            Zend_Registry::get('root') . '/application/modules/storefront/models/validate/', 
            'validate'
        );
        
        $this->addElement('select', 'title', array(
            'required'   => true,
            'label'      => 'Title',
            'multiOptions' => array('Mr' => 'Mr','Ms' => 'Ms','Miss' => 'Miss','Mrs' => 'Mrs')
        ));
        
        $this->addElement('text', 'firstname', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                'Alpha',
                array('StringLength', true, array(3, 128))
            ),
            'required'   => true,
            'label'      => 'Firstname',
        ));
        
        $this->addElement('text', 'lastname', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                'Alpha',
                array('StringLength', true, array(3, 128))
            ),
            'required'   => true,
            'label'      => 'lastname',
        ));
        
        $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', true, array(3, 128)),
                array('EmailAddress'),
                array('UniqueEmail', false, array($this->_model)),
            ),
            'required'   => true,
            'label'      => 'Email',
        ));
        
        $this->addElement('password', 'passwd', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 128))
            ),
            'required'   => true,
            'label'      => 'Password',
        ));
        
        $this->addElement('password', 'passwdVerify', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
               'PasswordVerification',
            ),
            'required'   => true,
            'label'      => 'Confirm Password',
        ));

        $this->addElement('submit', 'register', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Register',
        ));
    }
}
