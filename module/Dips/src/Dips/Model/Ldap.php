<?php
namespace Dips\Model;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\Ldap as AuthAdapter;

class Ldap
{
    public $ldap_instance;

    public function __construct($credentials)
    {
       $this->ldap_instance = $credentials;
    }

    public function getLdapInstance()
    {
      return $this->ldap_instance;
    }
}
