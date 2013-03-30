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
  
include_once(dirname(__FILE__).'/directory-access.class.php');
include_once(dirname(__FILE__).'/ldapexception.class.php');

/* class : cDirectoryAccess
 * 
 * history :
 *      First implementation on february/march 2013
 */
final class cLDAPDirectoryAccess extends cDirectoryAccess
{
    private $_ldci;                     // ldap connexion id
    private $_ldbi;                     // ldap binding id
    private $_autorizedAnonymous;       // to autorize ananymous acces on ldap host
    private $_dn;                       // ldap domain for connexion

   // private method's

    /* method : init
     * object : initialize default parameters
     */
    protected function init()
    {
        $this->setPort = 389;
        $this->setProtocole = 3;
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    private function validateProtocole($protocole)
    {
        if (filter_var($port, FILTER_VALIDATE_INT))
                if (($protocole < 2) and ($protocole > 3))
                        throw new Exception('Invalide protocole version specified for LDAP connexion class');
        else
            throw new Exception('Invalide protocole version value type specified for LDAP connexion class');
        return $protocole;
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    private function ldapcall($lrc)
    {
        if ($lrc == false)
            throw new cLDAPException($this->_ldci);
        return $lrc;
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    protected function internalConnect()
    {
        $this->_ldci = $this->ldapcall(ldap_connect($this->_host, $this->_port));
        if (empty($this->_dn)){
            $this->_ldbi = $this->bind('cn='.$this->_login, $this->_password);
        }else {
        $this->_ldbi = $this->bind('cn='.$this->_login.','.$this->_dn, $this->_password);}
    }

    protected function internalDisconnect()
    {
        if (isset($this->ldci))
            ldap_close($this->ldci);   
    }
    
    protected function internalReconnect()
    {
        $this->internalDisconnect();
        $this->internalReconnect();
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    private function internalUnbind()
    {

    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getLDCI()
    {
        return $this->_ldci;
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getLDBI()
    {
        return $this->_ldbi;            
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getLastErrorCode()
    {
        return ldap_errno($this->_ldci);            
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getLastErrorMessage()
    {
        return ldap_error($this->_ldci);
    }

    // based public method's

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function connectWithTLS($login, $password)
    {
        $this->setLoginAndPassword($login, $password);
        $this->InternalConnect();
        $this->ldapcall(ldap_start_tls($this->_ldci));
        $this->internalBind();

    }

    function anonymousConnect()
    {
        $this->setLoginAndPassword('', '');
        $this->InternalConnect();
        $this->internalBind();
    }

    function bind($login, $pwd)
    {
        return $this->ldapcall(ldap_bind($this->_ldci, $login, $pwd));
    }

    function unbind($ldci)
    {
        $this->ldapcall(ldap_unbin($ldci));

    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getCurrentLDAPDN($rs)
    {
        return $this->ldapcall(ldap_get_dn($this->_ldci, $rs));            
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getDN()
    {
        return $this->_dn;
    }
    
    /* method :
     *  desc. :
     * params :
     * return :
     */
    function setDN($dn)
    {
        $this->_dn = $dn;
    }
    /* method :
     *  desc. :
     * params :
     * return :
     */
    function freeResultSearch()
    {


    }

    // ldap entries access method's 

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getEntries($ldsr)
    {
        return $this->ldapcall(ldap_get_entries($this->_ldci, $ldsr));            
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getFirstEntries($ldsr)
    {
        return $this->ldapcall(ldap_get_first_enties($this->_ldci, $ldsr));
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getNextEntries($ldsr)
    {
        return $this->ldapcall(ldap_get_next_entries($this->_ldci, $ldsr));
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function addEntry($dn, $entry)
    {
        return $this->ldapcall(ldap_add($this->_ldci, $dn, $entry));            
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function deleteEntry($dn)
    {
        return $this->ldapcall(ldap_delete($this->_ldci, $dn));            
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function modifyEntry($dn, $entry)
    {
        return $this->ldapcall(ldap_modify($this->_ldci, $dn, $entry));
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function renameEntry($dn, $newdn, $newparent, $deleteold)
    {
        return $this->ldapcall(ldap_rename($this->_ldci, $dn, $newdn, $newparent, $deleteold));   
    }

    function countEntries($rs)
    {
        return $this->ldapcall(ldap_count_entries($this->_ldci, $rs));
    }

    // ldap attributes access method's

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getAttributes($rs)
    {
        return $this->ldapcall(ldap_get_attributes($this->_ldci, $rs));
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getFirstAttribute($rs)
    {
        return $this->ldapcall(ldap_first_attribute($this->_ldci, $rs));
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getNextAttribute($rs)
    {
        return $this->ldapcall(ldap_next_attribute($this->_ldci, $rs));
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function addAttribute($dn, $entry)
    {
        return $this->ldapcall(ldap_mod_add($this->_ldci, $dn , $entry));

    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function deleteAttribute($dn, $entry)
    {
        return $this->ldapcall(ldap_mod_del($this->_ldci, $dn , $entry));
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function modifyAttribute($dn, $entry)
    {
        return $this->ldapcall(ldap_mod_replace($this->_ldci, $dn, $entry));            
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function compare($dn, $attribute, $value)
    {
        return $this->ldapcall(ldap_compare($this->_ldci, $dn , $attribute, $value));
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    /*function List($dn, $filter, $attributes, $attrsonly, $sizelimit, $timelimit, $deref)
    {
        $this->ldapcall(ldap_list ($this->_ldci, $dn , $filter, $attributes, $attrsonly, $sizelimit, $timelimit, $deref));

    }*/

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function sort()
    {
        ldapcall(ldap_sort($this->_ldci, $result , $sortfilter));
    }

    // ldap option get and set function

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getOption($option)
    {      
        if ($this->ldapcall(ldap_get_option($this->_ldci, $option, &$result)))
            return $result;
        else
            return false;
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function setOption($option, $value)
    {
        $this->ldapcall(ldap_set_option($this->_ldci, $option, $value));            
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function search($dn, $filter, $attributes, $attrsonly, $sizelimit, $timelimit, $deref)
    {
        if ((!empty($dn)) or (!empty($filter)))
            return $this->ldapcall(ldap_search($this->_ldci, $dn, $filter, $attributes, $attrsonly, $sizelimit, $timelimit, $deref));
        else
            return null;
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function searchWithFilter($dn, $filter)
    {
        if ((!empty($dn)) && (!empty($filter)))
        {
            return ldap_search($this->_ldci, $dn, $filter);
        }
        else
        {
            return null;
        }
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function isExist($dn, $filter)
    {
        $result = true;
        $test = searchWithFilter($dn, $filter);
        if (!isset($test))
            $result = false;
        return $result;

    }
}



?>
