<?php
class Storefront_Validate_UniqueIdent extends Zend_Validate_Abstract
{
    const IDENT_EXISTS = 'identExists';

    protected $_messageTemplates = array(
        self::IDENT_EXISTS => 'Ident "%value%" already exists in our system',
    );

    protected $_model;
    protected $_method;

    public function __construct(SF_Model_Interface $model, $method)
    {
        $this->_model  = $model;
        $this->_method = $method;

        if (!method_exists($this->_model, $method)) {
            throw new SF_Exception('Method ' . $method . 'does not exist in model');
        }
    }

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);

        $found = call_user_func(array($this->_model, $this->_method), $value);
        
        if (null === $found) {
            return true;
        }

        $this->_error(self::IDENT_EXISTS);
        return false;
    }
}
