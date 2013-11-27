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
 * @author  StoneMor Partners 
 **/
class Ldap
{
    protected $ldap;
    protected $ldapOptions;
    protected $appLogger;
    protected $userName;
    protected $userPassword;
    protected $auth;
    protected $adapter;
    protected $roles;
    protected $emptyRoles = array('roles' => array());

    public function __construct(array $config, $appLogger)
    {
        $this->ldap = new ZendLdap();

        $this->setLdapOptions($config);
        $this->setApplicationLogger($appLogger);
        $this->setLdapTries($config);
        $this->setUserName($config);
        $this->setUserPassword($config);
        $this->bindToServer();
    }

    /** protected bindToServer(array $options)
     *
     * bindToServer
     * 
     * @param null
     * @access protected
     * @return void
     * @TODO create a JSON error handler to override the default.
     **/
    protected function bindToServer()
    {
        $tries = 0;
        for ($tries = 0; $tries < $this->ldapTries; $tries++) {
            foreach ($this->ldapOptions as $option => $server) {
                try {
                    $this->ldap->setOptions($server);
                    $this->ldap->bind($this->userName, $this->userPassword);
                    $tries = 999;
                    break; //the bind was successful, break out of all loops
                } 
                catch (LdapException $zle) {
                    if ($tries > $this->ldapTries) {
                        throw new Exception('Connect to ldap tries exceeded', $zle);
                    } 
                    $this->appLogger->crit($zle->getMessage());
                    continue;
                }
            }
        }
    }

    /** public findRolesForUser($user, $appName)
     *
     * findRolesForUser
     * return an array of assigned roles for user or null array if no roles
     * performs a recursive group search and a direct member group search
     * 
     * @param string user 
     * @param string $appName 
     * @access public
     * @return array
     **/
    public function findRolesForUser($user, $appName)
    {
        $objectCatPersonFilter = LdapFilter::equals('objectCategory', 'person');
        $samaccountNameFilter = LdapFilter::equals('samaccountname', $user);
        $objectCatGroupFilter = LdapFilter::equals('ObjectCategory', 'group');
        $searchString = LdapFilter::andFilter($objectCatPersonFilter, $samaccountNameFilter);

        $recursiveMemberGroupFilter = 'member:1.2.840.113556.1.4.1941:'; //magic AD recurse number
        $roleSearchPattern = '/cn=(.*?),ou=' . $appName . '+?,/i';

        $results = $this->ldap->searchEntries($searchString);

        foreach ($results as $result) {
            $fullUserDN = $result['distinguishedname'][0];
        }

        $f1 = LdapFilter::equals($recursiveMemberGroupFilter, $fullUserDN);
        $results = $this->ldap->searchEntries($f1);

        if (count($results) > 0) {
            foreach ($results as $result) {
                if (array_key_exists('memberof', $result)) {
                    foreach ($result['memberof'] as $memberof) {
                        if (preg_match($roleSearchPattern, $memberof, $roleMatches)) {
                            $this->roles[] = trim($roleMatches[1]);
                        }
                    }
                }

            }

            if (count($this->roles) > 0) {
                return array('roles' => $this->roles);

            } else {

                //Are they direct members of a group/role
                $directGroupMember = LdapFilter::equals('member', $fullUserDN);
                $searchString = LdapFilter::andFilter($objectCatGroupFilter, $directGroupMember);
                $results = $this->ldap->searchEntries($searchString);

                if (count($results) > 0) {
                    foreach ($results as $result) {
                        if (preg_match($roleSearchPattern, $result['dn'], $roleMatches)) {
                            $this->roles[] = trim($roleMatches[1]);
                        }
                    }

                    if (count($this->roles) <= 0) {
                        return $this->emptyRoles;
                    }

                } else {
                    return $this->emptyRoles;
                }

                return array('roles' => $this->roles);
            }
        } else {
            return $this->emptyRoles;
        }
    }

    /** public filterApproversByGroup($approvers, $filterGroup)
     *
     * filterApproversByGroup
     * Filter approvers by a given group
     * 
     * @param array $approvers 
     * @param string $filterGroup 
     * @access public
     * @return array
     **/
    public function filterApproversByGroup(array $approvers, $filterGroup)
    {
        $filteredApprovers = array();
        $f1 = LdapFilter::equals('objectClass', 'Person');
        $searchString = LdapFilter::andFilter($f1);

        foreach ($approvers as $approver) {

            $f2 = LdapFilter::equals('samaccountname', $approver['username']);
            $searchString = LdapFilter::andFilter($f1, $f2);

            $appSearchPattern = '/.*CN=' . $filterGroup . ',/';

            $results = $this->ldap->searchEntries($searchString);

            if (count($results) > 0 && array_key_exists('memberof', $results[0])) {
                $memberOf = $results[0]['memberof'];

                foreach ($memberOf as $member) {
                    if (preg_match($appSearchPattern, $member, $appMatch)) {
                        $filteredApprovers[] = array('username' => strtolower($approver['username']),
                                                     'displayname' => $approver['displayname']
                                                    );
                    }
                }
            }
        }

        if (count($filteredApprovers) > 0) {
            return $filteredApprovers;
        } else {
            return array($filteredApprovers[] = array('username' => '', 'displayname' => ''));
        }
    }

    /** public getAppAuthorization($uname, $appname)
     *
     * getAppAuthorization
     * 
     * @param mixed $uname 
     * @param mixed $appname 
     * @access public
     * @return void
     **/
    public function getAppAuthorization($uname, $appname)
    {
    }

    /** public setLdapOptions(array $config)
     *
     * setLdapOptions
     * 
     * @param array $config 
     * @access public
     * @return void
     **/
    public function setLdapOptions(array $config)
    {
        $this->ldapOptions = $config['ldap'];
    }

    /** public setApplicationLogger($appLogger)
     *
     * setApplicationLogger
     * 
     * @param object $appLogger 
     * @access public
     * @return void
     **/
    public function setApplicationLogger($appLogger)
    {
        $this->appLogger = $appLogger;
    }

    /** public setUserName(array $config)
     *
     * setUserName
     * 
     * @param array $config 
     * @access public
     * @return void
     **/
    public function setUserName(array $config)
    {
        $this->userName = $config['client']['username'];
    }

    /** public setUserPassword($config)
     *
     * setUserPassword
     * 
     * @param mixed $config 
     * @access public
     * @return void
     **/
    public function setUserPassword($config)
    {
        $this->userPassword = $config['client']['password'];
    }

    /** public setLdapTries($config)
     *
     * setLdapTries
     * 
     * @param array $config 
     * @access public
     * @return void
     **/
    public function setLdapTries($config)
    {
        $this->ldapTries = $config['ldaptries'];
    }
}
