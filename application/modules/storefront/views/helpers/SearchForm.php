<?php
/**
 * Storefront_View_Helper_SearchForm
 *
 * Helper for displaying the search form
 *
 * @category   Storefront
 * @package    Storefront_View_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_View_Helper_SearchForm extends Zend_View_Helper_Abstract
{
    public function searchForm()
    {
        $form = new Storefront_Form_Search_Base();
        $form->setAction($this->view->url(array(
            'controller' => 'index' ,
            'action' => 'search'
            ),
            'default'
        ));
        $form->setMethod('get');
        return $form;
    }
}