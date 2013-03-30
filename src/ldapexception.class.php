<?php
/*
 * Module :     ldapexception.class.php
 * Project :    Directory Access for PHP
 * Author :     Frédéric Libaud (http://www.libaudfrederic.fr)
 * Description :    This module is to defined exception class for LDAP API error
 *                  access call
 * 
 * Project URL is http: https://github.com/Libaud/directory-access-for-php.git
 * =============================================================================
 * history
 * =============================================================================
 *  18∕02/2013  Module creation for implementing ldap accessing class by
 *              Frédéric Libaud
 */

/*
 * class : LDAPException
 *         this class is to generate exception for LDAP API calling error
 * 
 * history :
 *      First implementation on february/march 2013
 */
class cLDAPException extends Exception
{
    function cLDAPException($ld)
    {
        if (!isset($ld))
        {
            $this->$code = $ldap_errno($ld);
            $this->$message = $ldap_error($ld);
        }
    }    

}

?>
