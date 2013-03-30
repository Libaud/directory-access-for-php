<?php
/*
 * Module : ldap-directory-access.class.php
 * Project : Directory Access for PHP
 * Author : Frédéric Libaud (http://www.libaudfrederic.fr)
 * Description :    This module is to defined class to accessing
 *                  to LDAP directory with PHP API
 * =============================================================================
 * history
 * =============================================================================
 *  18∕02/2013  Module creation for implementing ldap accessing class by
 *              Frédéric Libaud
 */

namespace ldap
{
    require_once('ldap.class.php');
    
    class cMSADLDAP extends cLDAP
    {
        function searchUser($user)
        {
            return $this->search($dn, 'sAMAccountName="'.$username.'"');
            return $this->search($dn, preg_replace('%username%', $user, $this->$_userSearchFilter));
        }
        
    }
}

?>
