<?php
/**
 * @namespace SF\Model\Acl
 */
namespace SF\Model\Acl;

/**
 * Acl Interface
 *
 * @category   Storefront
 * @package    SF_Model_Acl
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface Acl
{
    public function setIdentity($identity);
    public function getIdentity();
    public function checkAcl($action);
    public function setAcl(SF_Acl_Interface $acl);
    public function getAcl();
}
