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

    /**
     * @param mixed $data
     *
     * @return JsonModel
     * @throws \Exception
     */
    public function get($data)
    {
        try {
            $this->isValidApiRequest($data);
            $data = $this->detectRunTimeEnvironment($data);

            $roles = $this->ldap->findRolesForUser($data['uname'], $data['appname']);
            $approvers = array($roles, $this->getLocationIdsByUser($data));
        }
        catch (\Exception $e) {
          $logData = $e->getMessage() . ':' . $e->getFile() . ':' . $e->getCode() . ':' 
            . print_r($data,true);
            $this->getServiceLocator()->get('Zend\Log')->crit($logData); 
            throw new \Exception($e->getMessage());
        }

        // Until we get a separate domain controller for AD we have to do this tacky hack
        // to remove the prepended app name from the role before sending it back. 
        $approvers = $this->removeAdAppPrefix($approvers, $data['appname']);

        return $this->getJson($approvers);
    }

    /**
     * @param array $data
     *
     * @return mixed
     * @TODO add to a factory and inject into controller
     */
    public function getLocationIdsByUser($data)
    {
        return $this->rollUpStoredProcedure->getLocationIdsByUser($data);
    }
}
