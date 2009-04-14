<?php
/**
 * Add new category
 *
 * @category   Storefront
 * @package    Storefront_Form
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Form_Catalog_Category_Add extends SF_Form_Abstract
{
    public function init()
    {
        // add path to custom validators & filters
        $this->addElementPrefixPath(
            'Storefront_Validate',
            APPLICATION_PATH . '/modules/storefront/models/validate/',
            'validate'
        );

        $this->addElementPrefixPath(
            'Storefront_Filter',
            APPLICATION_PATH . '/modules/storefront/models/filter/',
            'filter'
        );
        
        $this->setMethod('post');
        $this->setAction('');

        $this->addElement('text', 'name', array(
            'label' => 'Name',
            'filters' => array('StringTrim'),
            'required' => true,
        ));
        $this->addElement('text', 'ident', array(
            'label' => 'Ident',
            'filters' => array('StringTrim','Ident'),
            'validators' => array(
                    array('UniqueIdent', true, array($this->getModel(), 'getCategoryByIdent'))
            ),
            'required' => true,
        ));

        // get the select
        $form = new Storefront_Form_Catalog_Category_Select(
            array('model' => $this->getModel())
        );
        $element = $form->getElement('categoryId');
        $element->clearDecorators()->loadDefaultDecorators();
        $element->setName('parentId')
                ->setRequired(true)
                ->setLabel('Select Parent');
        $this->addElement($element,'parentId');

        $this->addElement('submit', 'add', array(
            'label' => 'Add Category',
            'decorators' => array('ViewHelper',array('HtmlTag',array('tag' => 'dd'))),
        ));
    }
}
