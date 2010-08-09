<?php
namespace Storefront\Model\Resource;

use SF\Model\DbTable\AbstractRow as SFModelDbRowAbstract;

class Category extends SFModelDbRowAbstract implements Category\Category
{
    public function getParentCategory()
    {
        return $this->findParentRow('Storefront\\Model\\Resource\\CategoryResource', 'SubCategory');
    }
}