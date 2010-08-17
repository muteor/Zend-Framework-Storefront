<?php
namespace Cms\Model\Resource;

use SF\Model\DbTable\AbstractRow as SFModelDbRowAbstract,
    Cms\Model\Resource\Page;

/**
 * Cms_Resource_Page_Item
 *
 * @category   Cms
 * @package    Cms_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Page extends SFModelDbRowAbstract implements Page\Page
{}
