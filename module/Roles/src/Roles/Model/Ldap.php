<?php
namespace Roles\Model;

use Zend\Authentication\AuthenticationService as AuthService;
use Zend\Authentication\Adapter\Ldap as AuthAdapter;
use Zend\Ldap\Ldap as ZendLdap;
use Zend\Ldap\Filter as LdapFilter;
use Zend\Ldap\Converter\Converter as LdapConverter;
use Zend\Ldap\Exception\LdapException as LdapException;

/**
 * Ldap 
 * 
 * @package Webdav
 * @version //autogen//
 * @author  StoneMor Partners 
 * @todo create a JSON error handler to override the existing html one
 */
class Ldap
{
    protected $ldap;
    protected $ldapOptions;
    protected $userName;
    protected $userPassword;
    protected $auth;
    protected $adapter;
    protected $roles = array();

    public function __construct(array $config)
    {
        $this->ldap = new ZendLdap();

        $this->setLdapOptions($config);
        $this->setUserName($config);
        $this->setUserPassword($config);
        $this->bindToServer($this->ldapOptions);
    }

    /* protected bindToServer(array $options) {{{ */ 
    /**
     * bindToServer
     * 
     * @param array $options 
     * @access protected
     * @return void
     * @TODO create a JSON error handler to override the default.
     */
    protected function bindToServer(array $options)
    {
        foreach ($options as $option => $server) {
          try {
            $this->ldap->setOptions($server);
            $this->ldap->bind($this->getUserName(), $this->getUserPassword());
            $acctname = $this->ldap->getCanonicalAccountName($this->getUserName());
          } catch (LdapException $zle) {
              echo 'StoneMor LDAP Error:  ' . $zle->getMessage() . "\n";
              //@TODO Need to log these exceptions
              if ($zle->getCode() === LdapException::LDAP_X_DOMAIN_MISMATCH) {
                 continue;
              }
              continue;
          }
        }
    }

    /* public findRolesForUser($user, $appName)
    /**
     * findRolesForUser
     * return an array of assigned roles for user or null array if no roles
     * 
     * @param string user 
     * @param string $appName 
     * @access public
     * @return array
     */
    public function findRolesForUser($user, $appName)
    {
        $f1 = LdapFilter::equals('objectCategory', 'person');
        $f2 = LdapFilter::equals('samaccountname', $user);
        $searchString = LdapFilter::andFilter($f1, $f2);

        $appSearchPattern = '/.*OU=' . strtoupper($appName) . '.*/';
        $roleSearchPattern = '/CN=(.*?),/';

        $results = $this->ldap->searchEntries($searchString);

        if (count($results) > 0) {
          $members = $results[0]['memberof'];

          foreach ($members as $member) {
            if (preg_match($appSearchPattern, $member, $appMatch)) {
              if (preg_match($roleSearchPattern, $appMatch[0], $roleMatch)) {
                $this->roles[] = $roleMatch[1];
              }
            }
          }

          if (count($this->roles) > 0) {
            return array('roles' => $this->roles);
          } else {
            return array('roles');
          }

        } else {
          return array('roles');
        }
    }

    public function findLocationsForUser($user)
    {
      $locations = array('locations' => array (
                          '118',
                          '885',
                          '115'
                        )); 
      return $locations;
    }
    
    public function setLdapOptions(array $config)
    {
        $this->ldapOptions = $config['ldap'];
    }

    public function getLdapOptions()
    {
        return $this->ldapOptions;
    }

    /* public setUserName(array $config)
    /**
     * setUserName
     * 
     * @param array $config 
     * @access public
     * @return void
     */
    public function setUserName(array $config)
    {
        $this->userName = $config['client']['username'];
    }

    /* public getUserName()
    /**
     * getUserName
     * 
     * @access public
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /* public setUserPassword($config)
    /**
     * setUserPassword
     * 
     * @param mixed $config 
     * @access public
     * @return void
     */
    public function setUserPassword($config)
    {
        $this->userPassword = $config['client']['password'];
    }

    /* public getUserPassword()
    /**
     * getUserPassword
     * 
     * @access public
     * @return string
     */
    public function getUserPassword()
    {
      return $this->userPassword;
    }
}
