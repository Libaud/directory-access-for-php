<?php
/*
 * Module :     directory-access.class.php
 * Project :    Directory Access for PHP
 * Author :     Frédéric Libaud (http://www.libaudfrederic.fr)
 * Description :    This module is to defined to contains abstract base class
 *                  to accessing directories with API for exemple
 * 
 * Project URL is http: https://github.com/Libaud/directory-access-for-php.git
 * =============================================================================
 * history
 * =============================================================================
 *  18∕02/2013  Module creation for implemented ldap abstract access class by
 *              Frédéric Libaud
 */

/* class : cDirectoryAccess
 * 
 * history :
 *      First implementation on february/march 2013
 */
abstract class cDirectoryAccess
{
    protected $_host = "";
    protected $_port = 0;
    protected $_protocole = 0;
    protected $_login = "";
    protected $_password = "";
    protected $_connected = false;
    protected $_errorException = null;

    // constructor and destructor

    function __construct($host, $port)
    {
        //$this->_connected = false;
        $this->_host = $this->validateHost($host);
        $this->_port = $this->validatePort($port);
        $this->init();
    }

    /* method :
     * object :
     * 
     */
    function __desctruct()
    {
        if ($this->_connected)
            $this->disconnect();
    }

    // protected method's

    function setLoginAndPasswor($login, $password)
    {
        $this->_login = validateLogin($login);
        $this->_password = validatePassword($passowrd);
    }

    // abstract method's

    abstract protected function init();
    abstract protected function internalConnect();
    abstract protected function internalDisconnect();
    abstract protected function internalReconnect();

    /* method : validateHost
     *  desc. : to host url or ip
     * params :
     *      $host : host url or ip
     * return : if validate host url or ip or thrown exception error
     */
    private function validateHost($host)
    {
        $testip = filter_var($host, FILTER_VALIDATE_IP);
        $testurl = filter_var($host, FILTER_VALIDATE_URL);
        if ((!testip) and (!$testurl))
            throw new Exception('Invalide ip or url for LDAP connexion class');
        return $host;
    }

    /* method : validatePort
     *  desc. : valide the port number value for ldap host access
     * params :
     *      $port : port number
     * return : if validate return port number else throw exception error
     */
    private function validatePort($port)
    {
        $test = filter_var($port, FILTER_VALIDATE_INT);
        if (!test)
            throw new Exception('Invalide port number for LDAP connexion class');
        return $port;
    }

    // public method's

    /* method :
     *  desc. :
     * params :
     * return :
     */
    public function connect($login, $password)
    { 
        $this->setLogin($login);
        $this->setPassword($password);
        $this->InternalConnect();
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    public function disconnect()
    {
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function reconnect()
    {
        if (!isConnected())
        {
            $this->InternalConnect();
            $this->internalBind();
        }                    

    }

    // abstract public method's

    // properties access method's

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getHost()
    {
        return $this->_host;
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function setHost($host)
    {
       $this->_host = validateHost($host); 
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getPort()
    {
        return $this->_port;
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function setPort($port)
    {
        $this->_port = validatePort($port);
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function getProtocole()
    {
        return $this->_protocole;
    }

    /* method :
     *  desc. :
     * params :
     * return :
     */
    function setProtocole($protocole)
    {
        $this->_protocole = validateProtocole($protocole);           
    }

    function getLogin()
    {
        return $this->_login;
    }

    function setLogin($login)
    {
        $this->_login = $login;
    }

    function getPassword()
    {
        return $this->_password;
    }

    function setPassword($password)
    {
        $this->_password = $password;
    }

    function isConnected()
    {
        return $this->_connected;
    }


}   // cAbstractLDAP class declaration

?>
