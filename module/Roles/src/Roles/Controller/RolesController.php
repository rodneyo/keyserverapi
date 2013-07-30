<?php
namespace Roles\Controller;

use Zend\View\Model\JsonModel;

class RolesController extends ApiBaseController
{
    protected $rollUpStoredProcedure;
    protected $ldap;

    public function __construct($ldap, $rollUpDbProcedure)
    {
        $this->rollUpStoredProcedure = $rollUpDbProcedure;
        $this->ldap = $ldap;
    }

    /* public get($data)
    /**
     * get
     * 
     * @param mixed $data 
     * @access public
     * @return JSON
     */
    public function get($data)
    {
        try {
            $this->isValidApiRequest($data);
            $data = $this->detectRunTimeEnvironment($data);

            $roles = $this->ldap->findRolesForUser($data['uname'], $data['appname']);
            $approvers = array($roles, $this->getLocationIdsByUser($data['uname']));
        }
        catch (\Exception $e) {
          $logData = $e->getMessage() . ':' . $e->getFile() . ':' . $e->getCode() . ':' 
            . print_r($data,true);
            $this->getServiceLocator()->get('Zend\Log')->crit($logData); 
            throw new \Exception($e->getMessage());
        }

        // Until we get a separate domain controller for AD we have to do this tacky hack
        // to remove the prepended app name from the role before sending it back. 
        foreach ($approvers[0] as $key=>$value) {
          $regex = '/' .  $data['appname'] . '/i';
          $approvers[0][$key] = preg_replace($regex, '', $value);
        }
        return $this->getJson($approvers);
    }

    /* public getLocationIdsByUser($user)
    /**
     * getLocationIdsByUser
     * 
     * @param string user 
     * @access public
     * @return array
     * @TODO add to a factory and inject into controller
     */
    public function getLocationIdsByUser($user)
    {
        return $this->rollUpStoredProcedure->getLocationIdsByUser($user);
    }
}
