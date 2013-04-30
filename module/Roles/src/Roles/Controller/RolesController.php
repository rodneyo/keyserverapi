<?php
namespace Roles\Controller;

use Zend\View\Model\JsonModel;

class RolesController extends ApiBaseController
{
    protected $rollUpStoredProcedure;

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

            $ldap = $this->getLdap();

            $roles = $ldap->findRolesForUser($data['uname'], $data['appname']);
            $data = array($roles, $this->getLocationIdsByUser($data['appname']));
        }
        catch (\Exception $e) {
          $logData = $e->getMessage() . ':' . $e->getFile() . ':' . $e->getCode() . ':' 
            . print_r($data,true);
            $this->getServiceLocator()->get('Zend\Log')->crit($logData); 
            throw new \Exception($e->getMessage());
        }

        return $this->getJson($data);
    }

    /* protected getJson($data)
     * getJson
     * 
     * @param mixed $data 
     * @access protected
     * @return zend JsonModel
     */
    protected function getJson(array $data)
    {
      return new JsonModel($data);
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
        if (!$this->rollUpStoredProcedure) {
            $sm = $this->getServiceLocator();
            $this->rollUpStoredProcedure = $sm->get('RollUpStoredProcedure');
            return $this->rollUpStoredProcedure->getLocationIdsByUser($user);
        }
    }

    /* protected getLdap()
    /**
     * getLdap
     * 
     * @access protected
     * @return obj 
     * @TODO add to a factory and inject into controller
     */
    protected function getLdap()
    {
        return $this->getServiceLocator()->get('Roles\Model\Ldap');
    }

    protected function getIdentifier($routeMatch, $request)
    {
       return $routeMatch->getParams();
    }
}
