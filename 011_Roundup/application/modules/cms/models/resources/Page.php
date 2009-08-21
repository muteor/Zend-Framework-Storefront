<?php
/** Cms_Resource_Page_Item */
if (!class_exists('Cms_Resource_Page_Item')) {
    require_once dirname(__FILE__) . '/Page/Item.php';
}

/**
 * Cms_Resource_Page
 *
 * @category   Cms
 * @package    Cms_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Cms_Resource_Page extends SF_Model_Resource_Db_Table_Abstract implements Cms_Resource_Page_Interface
{
    protected $_name = 'page';
    protected $_primary = 'pageId';
    protected $_rowClass = 'Cms_Resource_Page_Item';

    public function getPageById($id)
    {
        return $this->find($id)->current();
    }
}