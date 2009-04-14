<?php
class Storefront_Form_Catalog_Product_Image_Add extends SF_Form_Abstract
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('');
        $this->setAttrib('enctype', 'multipart/form-data');

        $fileDestination = realpath(APPLICATION_PATH . '/../public/images/product');

        $this->addElement('file','full', array(
            'label' => 'Full Image',
            'required' => true,
            'destination' => $fileDestination,
            'validators' => array(
                array('Count', false, array(1)),
                array('Size', false, array(1048576*5)),
                array('Extension', false, array('jpg,png,gif')),
            ),
        ));
        $this->addElement('file','thumbnail', array(
            'label' => 'Thunbnail Image',
            'required' => true,
            'destination' => $fileDestination,
            'validators' => array(
                array('Count', false, array(1)),
                array('Size', false, array(1048576*5)),
                array('Extension', false, array('jpg,png,gif')),
            ),
        ));

        $this->addElement('select', 'isDefault', array(
            'required'   => true,
            'label'      => 'Default?',
            'multiOptions' => array('Yes' => 'Yes','No' => 'No'),
        ));

        $this->addElement('submit', 'add', array(
            'label' => 'Upload',
            'decorators' => array('ViewHelper',array('HtmlTag',array('tag' => 'dd'))),
        ));

        $this->addElement('hidden', 'productId', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'decorators' => array('viewHelper',array('HtmlTag', array('tag' => 'dd', 'class' => 'noDisplay')))
        ));
    }
}