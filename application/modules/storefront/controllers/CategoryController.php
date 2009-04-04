<?php
/**
 * CategoryController
 * 
 * @category   Storefront
 * @package    Storefront_Controllers
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_CategoryController extends Zend_Controller_Action 
{
	/**
	 * This action is never directly called and
	 * is used by the ActionStack plugin to populate
	 * the main(top level) category menu.
	 */
    public function indexAction() 
	{    
		$id = $this->_getParam('categoryId', 0);
        $catalogModel = new Storefront_Model_Catalog();
        $this->view->categories = $catalogModel->getCategoriesByParentId($id);

        $this->_helper->viewRenderer->setResponseSegment($this->_getParam('responseSegment'));
	}
}
