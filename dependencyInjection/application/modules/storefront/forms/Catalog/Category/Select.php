<?php
/**
 * Category Select
 *
 * @category   Storefront
 * @package    Storefront_Form
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Form_Catalog_Category_Select extends SF_Form_Abstract
{
    public function init()
    {
        $this->setMethod('post');

        $categories = $this->getModel()->getCategories();
        $cats = array();
        foreach($categories as $category) {
            $cats[$category->categoryId] = $category->name; 
        }

        $this->addElement('select', 'categoryId', array(
            'label' => 'Select Category: ',
            'decorators' => array('ViewHelper','Label'),
            'multiOptions' => $cats
        ));
        $this->addElement('submit', 'View', array(
            'decorators' => array('ViewHelper'),
        ));
    }
}
