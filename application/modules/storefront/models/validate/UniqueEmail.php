<?php
namespace Storefront\Model\Validate;

use Zend\Validator,
    Storefront\Model;

class UniqueEmail extends Validator\AbstractValidator
{
    const EMAIL_EXISTS = 'emailExists';

    protected $_messageTemplates = array(
        self::EMAIL_EXISTS => 'Email "%value%" already exists in our system',
    );

    public function __construct(Model\User $model)
    {
        $this->_model = $model;
    }

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);
        $currentUser = isset($context['userId']) ? $this->_model->getUserById($context['userId']) : null;
        $user = $this->_model->getUserByEmail($value, $currentUser);
        
        if (null === $user) {
            return true;
        }

        $this->_error(self::EMAIL_EXISTS);
        return false;
    }
}
