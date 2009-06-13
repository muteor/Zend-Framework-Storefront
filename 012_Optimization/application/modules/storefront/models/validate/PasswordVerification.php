<?php
class Storefront_Validate_PasswordVerification extends Zend_Validate_Abstract
{
    const NOT_MATCH = 'notMatch';

    protected $_messageTemplates = array(
        self::NOT_MATCH => 'Passwords do not match'
    );

    public function isValid($value, $context = null)
    {
        $value = (string) $value;
        $this->_setValue($value);

        if (is_array($context)) {
            if (isset($context['passwd'])
                && ($value == $context['passwd']))
            {
                return true;
            }
        } elseif (is_string($context) && ($value == $context)) {
            return true;
        }

        $this->_error(self::NOT_MATCH);
        return false;
    }
}
