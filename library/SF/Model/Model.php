<?php
/**
 * @namespace SF\Model
 */
namespace SF\Model;

/**
 * Model interface
 * 
 * All models use this interface
 * 
 * @category   Storefront
 * @package    SF_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface Model
{
    public function __construct($options = null);
    public function getResource($name);
    public function getForm($name);
    public function init();
}
