<?php
/**
 * The base search form
 *
 * @category   Storefront
 * @package    Storefront_Form
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Form_Search_Base extends SF_Form_Abstract
{
    public function init()
    {
        $this->setName('searchForm');
        
        $this->addElement('text', 'query', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => 'Search',
            'decorators' => array('ViewHelper',array('HtmlTag', array('tag' => 'dd')))
        ));

        $this->addElement('text', 'pricefrom', array(
            'filters'    => array('StringTrim'),
            'required'   => false,
            'label'      => 'Price: ',
            'decorators' => array('ViewHelper', 'Label'),
            'style'      => 'width: 50px; float: left;'
        ));
        $this->addElement('text', 'priceto', array(
            'filters'    => array('StringTrim'),
            'required'   => false,
            'label'      => 'to',
            'decorators' => array('ViewHelper', array('Label', array('style' => 'padding: 5px;'))),
            'style'      => 'width: 50px; float: left;'
        ));
        $this->addDisplayGroup(array('pricefrom','priceto'), 'prange', array(
            'decorators' => array('FormElements', array('HtmlTag', array('tag' => 'dd', 'class' => 'clearfix'))),
        ));
        
        $this->addElement('submit', 'search', array(
            'required' => false,
            'ignore'   => true,
            'decorators' => array('ViewHelper',array('HtmlTag', array('tag' => 'dd', 'id' => 'form-submit')))
        ));
    }
}
