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

            $roles = $this->ldap->findRolesForUser($data['uname'], $data['appname']);
            $data = array($roles, $this->getLocationIdsByUser($data['uname']));
        }
        catch (\Exception $e) {
          $logData = $e->getMessage() . ':' . $e->getFile() . ':' . $e->getCode() . ':' 
            . print_r($data,true);
            $this->getServiceLocator()->get('Zend\Log')->crit($logData); 
            throw new \Exception($e->getMessage());
        }

        return $this->getJson($data);
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
