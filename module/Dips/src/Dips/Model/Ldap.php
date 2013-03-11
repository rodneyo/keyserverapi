<?php
namespace Dips\Model;

use Zend\Authentication\AuthenticationService as AuthService;
use Zend\Authentication\Adapter\Ldap as AuthAdapter;
use Zend\Ldap\Ldap as ZendLdap;
use Zend\Ldap\Filter as LdapFilter;
use Zend\Ldap\Converter\Converter as LdapConverter;

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

    public function __construct(array $config)
    {
        $this->ldap = new ZendLdap();

        $this->setLdapOptions($config);
        $this->setUserName($config);
        $this->setUserPassword($config);
        $this->bindToServer($this->ldapOptions);

        /*
        $this->auth = new AuthService();

        $this->adapter = new AuthAdapter($this->getLdapOptions(),
                                         $this->getUserName(),
                                         $this->getUserPassword() 
        );

        $result = $this->auth->authenticate($this->adapter);
        var_dump(get_class_methods($result));
        var_dump($result->getMessages());
         */

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
          } catch (Zend\Ldap\Exception\LdapException $zle) {
              //echo 'StoneMor LDAP Error:  ' . $zle->getMessage() . "\n"; exit;
              if ($zle->getCode() === Zend\Ldap\Exception\LdapException::LDAP_X_DOMAIN_MISMATCH) {
                 continue;
              }
              continue;
          }
        }
    }
    /* }}} */

    public function findRolesForUser($user, $appName)
    {
        $filter = LdapFilter::equals('samaccountname', $user);
        $search_string = "(&(objectCategory=person){$filter})";

        $results = $this->ldap->searchEntries($search_string);

        if (count($results) > 0) {
          foreach ($results as $result) {
            foreach ($result as $key => $value) {
              if ($key === 'memberof') {
                  foreach ($result[$key] as $entry) {
                    preg_match('/CN=(.*?),/', $entry, $cnMatches);
                    $groups[] = $cnMatches[1];
                  }
              }
            }
          }
          return array('roles' => $groups);
        } else {
          return array('roles' => '');
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

    public function setUserName(array $config)
    {
        $this->userName = $config['client']['username'];
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function setUserPassword($config)
    {
        $this->userPassword = $config['client']['password'];
    }

    public function getUserPassword()
    {
      return $this->userPassword;
    }
}
