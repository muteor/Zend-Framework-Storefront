<?php
namespace Cms\Model\Resource;

use SF\Model\DbTable\AbstractTable as SFModelDbTableAbstract,
    Cms\Model\Resource\Page;

/**
 * Cms_Resource_Page
 *
 * @category   Cms
 * @package    Cms_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class PageResource extends SFModelDbTableAbstract implements Page\Resource
{
    protected $_name = 'page';
    protected $_primary = 'pageId';
    protected $_rowClass = 'Cms\\Model\\Resource\\Page';

    public function getPageById($id)
    {
        return $this->find($id)->current();
    }
}