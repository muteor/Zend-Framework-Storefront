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
        $this->addElement('text', 'query', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => 'Search',
            'decorators' => array('ViewHelper',array('HtmlTag', array('tag' => 'dd')))
        ));

        $this->addElement('submit', 'search', array(
            'required' => false,
            'ignore'   => true,
            'decorators' => array('ViewHelper',array('HtmlTag', array('tag' => 'dd', 'id' => 'form-submit')))
        ));
    }
}
