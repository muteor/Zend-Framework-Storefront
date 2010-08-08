<?php
namespace Storefront\Form\User;

use SF\Form,
    Storefront\Model,
    Zend\Form as ZendForm;

/**
 * The base user form
 *
 * @category   Storefront
 * @package    Storefront_Form
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Base extends Form\AbstractForm
{
    public function init()
    {
        // add path to custom validators
        $this->addElementPrefixPath(
            'Storefront\\Model\\Validate',
            APPLICATION_PATH . '/modules/storefront/models/validate/',
            ZendForm\Element::VALIDATE
        );

        $this->addElement('select', 'title', array(
            'required'   => true,
            'label'      => 'Title',
            'multiOptions' => array('Mr' => 'Mr','Ms' => 'Ms','Miss' => 'Miss','Mrs' => 'Mrs'),
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
                array('UniqueEmail', false, array($this->getModel())),
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

        $this->addElement('select', 'role', array(
            'required'   => true,
            'label'      => 'Role',
            'multiOptions' => array('Customer' => 'Customer', 'Admin' => 'Admin'),
        ));

        $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'decorators' => array('ViewHelper',array('HtmlTag', array('tag' => 'dd', 'id' => 'form-submit')))
        ));

         $this->addElement('hidden', 'userId', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'decorators' => array('viewHelper',array('HtmlTag', array('tag' => 'dd', 'class' => 'noDisplay')))
        ));
    }
}
