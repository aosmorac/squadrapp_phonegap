<?php
/**
 * @see Zend_Validate_Abstract
 */

class App_Validate_Password extends Zend_Validate_Abstract
{
    const INVALID  = 'passwordInvalid';
    const NOT_STRONG = 'passwordNotStrong';
    const NOT_UPPER = 'passwordNotUpper';
    const NOT_LOWER = 'passwordNotLower';
    const NOT_NUMBER = 'passwordNotNumber';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_STRONG => "El password no es lo suficientemente fuerte. Debe contener por lo menos un caracter especial.",
        self::NOT_UPPER => "El password no es lo suficientemente fuerte. Debe contener por lo menos un caracter en mayúscula.",
        self::NOT_LOWER => "El password no es lo suficientemente fuerte. Debe contener por lo menos un caracter en minúscula.",
        self::NOT_NUMBER => "El password no es lo suficientemente fuerte. Debe contener por lo menos un caracter numérico.",
        self::INVALID  => "Tipo de dato inválido",
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * Retorna true si el password es lo suficientemente fuerte
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value) {
        if (!is_string($value)) {
            $this->_error(self::INVALID);
            return false;
        }

        $this->_setValue($value);
        if (!preg_match('/[a-z]/',$value)){
            $this->_error(self::NOT_LOWER);
            return false;
        }
        if (!preg_match('/[A-Z]/',$value)){
            $this->_error(self::NOT_UPPER);
            return false;
        }
        if (!preg_match('/[0-9]/',$value)){
            $this->_error(self::NOT_NUMBER);
            return false;
        }
        $especiales = preg_replace('/[a-zA-Z0-9]/', "", $value);
        if( strlen($value) >0 && strlen($especiales)==0 ) {
            $this->_error(self::NOT_STRONG);
            return false;
        }
        return true;
    }

}
