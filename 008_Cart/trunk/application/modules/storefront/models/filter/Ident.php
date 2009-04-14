<?php
class Storefront_Filter_Ident implements Zend_Filter_Interface
{
    public function filter($value)
    {
        $find    = array( '`', '&',   ' ', '"', "'" );
        $replace = array( '',  'and', '-', '',  '', );
        $new = str_replace( $find, $replace,$value);

        $noalpha = 'ÁÉÍÓÚÝáéíóúýÂÊÎÔÛâêîôûÀÈÌÒÙàèìòùÄËÏÖÜäëïöüÿÃãÕõÅåÑñÇç@°ºª';
        $alpha   = 'AEIOUYaeiouyAEIOUaeiouAEIOUaeiouAEIOUaeiouyAaOoAaNnCcaooa';

        $new = substr( $new, 0, 255 );
        $new = strtr( $new, $noalpha, $alpha );

        // not permitted chars are replaced with "-"
        $new = preg_replace( '/[^a-zA-Z0-9_\+]/', '-', $new );

        //remove -----'s
        $new = preg_replace( '/(-+)/', '-', $new );

        return rtrim( $new, '-' );
    }
}
