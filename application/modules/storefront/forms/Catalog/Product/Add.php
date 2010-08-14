<?php
namespace Storefront\Form\Catalog\Product;

use SF\Form,
    Storefront\Model,
    Zend\Form as ZendForm;

class Add extends Form\AbstractForm
{
    public function init()
    {
        // add path to custom validators & filters
        $this->addElementPrefixPath(
            'Storefront\\Model\\Validate',
            APPLICATION_PATH . '/modules/storefront/models/validate/',
            ZendForm\Element::VALIDATE
        );

        $this->addElementPrefixPath(
            'Storefront\\Model\\Filter',
            APPLICATION_PATH . '/modules/storefront/models/filter/',
            ZendForm\Element::FILTER
        );

        $this->setMethod('post');
        $this->setAction('');

        // get category select
        $form = new Storefront_Form_Catalog_Category_Select(
            array('model' => $this->getModel())
        );
        $element = $form->getElement('categoryId');
        $element->clearDecorators()->loadDefaultDecorators();
        $element->setRequired(true);
        $this->addElement($element);

        $this->addElement('text', 'name', array(
            'label' => 'Name',
            'filters' => array('StringTrim'),
            'required' => true,
        ));

       $this->addElement('text', 'ident', array(
            'label' => 'Ident',
            'filters' => array('StringTrim','Ident'),
            'validators' => array(
                    array('UniqueIdent', true, array($this->getModel(), 'getProductByIdent'))
            ),
            'required' => true,
        ));

        $this->addElement('text', 'shortDescription', array(
            'label' => 'Short Description',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(array('StringLength',true, array(1,255))),
        ));

        $this->addElement('text', 'price', array(
            'label' => 'Price',
            'required' => true,
            'validators' => array('Float')
        ));

        $this->addElement('text', 'discountPercent',array(
            'label' => 'Discount %',
            'value' => 0,
            'required' => true,
            'validators' => array('Int'),
        ));

        $this->addElement('select', 'taxable', array(
            'label' => 'Taxable?',
            'multiOptions' => array('Yes' => 'Yes', 'No' => 'No')
        ));

        $this->addElement('textarea', 'description', array(
            'label' => 'Full Description',
            'filters' => array('StringTrim'),
            'required' => true,
        ));

        $this->addDisplayGroup(array(
            'categoryId',
            'name',
            'ident',
            'shortDescription',
            'price',
            'discountPercent',
            'taxable',
            'description'
        ), 'productInfo', array('legend' => 'Product Information'));

        $this->addElement('submit', 'add', array(
            'label' => 'Add Product',
            'decorators' => array('ViewHelper',array('HtmlTag',array('tag' => 'dd'))),
        ));
    }
}