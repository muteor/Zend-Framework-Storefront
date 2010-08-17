<?php
namespace Storefront\View\Helper;

use Zend\View\Helper,
    Storefront\Form;
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
class SearchForm extends Helper\AbstractHelper
{
    public function direct()
    {
        $form = new Form\Search\Base();
        $form->setAction($this->view->Url(array(
            'controller' => 'index' ,
            'action' => 'search'
            ),
            'default'
        ));
        $form->setMethod('get');
        return $form;
    }
}