<?php
namespace Cms\Model;

use SF\Model;

class Page extends Model\AbstractModel
{
    public function getPageById($id)
    {
        $id = (int) $id;
        return $this->getResource('page')->getPageById($id);
    }
}
