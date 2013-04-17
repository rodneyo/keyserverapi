<?php
namespace Roles\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Roles\Model\Apikeys\AppTable;
use Roles\Model\Apikeys\ClientTable;
use Roles\Model\Apikeys\ClientAppTable;

class RolesController extends AbstractRestfulController
{
    protected $appTable;
    protected $clientTable;
    protected $clientAppTable;
    protected $errorResponseMessage = 'Method Not Supported';

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
            $this->getClientTable();

            $clientId = $this->clientTable->hasValidApiKey($this->getRequest());

            $this->getClientAppTable();
            $this->clientAppTable->hasEnabledApp($clientId, $data['appname']);

            $config = $this->getServiceLocator()->get('config');

            $ldap = $this->getLdap(); //from the service manager, reduces resource coupling

            $roles = $ldap->findRolesForUser($data['uname'], $data['appname']);
            $locations = $ldap->findLocationsForUser($data['uname']);
            $data = array($roles, $locations);
        }
        catch (\Exception $e) {
          $logData = $e->getMessage() . ':' . $e->getFile() . ':' . $e->getCode() . ':' 
            . print_r($data,true);
            $this->getServiceLocator()->get('Zend\Log')->crit($logData); 
            throw new \Exception($e->getMessage());
        }

        return $this->getJson($data);
    }

    /*
     * edit, delete, create, update and options methods must be implemented
     * due to the inheritance from AbstractRestfulController
     */
    public function edit($id)
    {
       throw new \Exception ($this->errorResponseMessage);
    }

    public function delete($id)
    {
       throw new \Exception ($this->errorResponseMessage);
    }

    public function create($id)
    {
        throw new \Exception ($this->errorResponseMessage);
    }

    public function update($id, $data)
    {
       throw new \Exception ($this->errorResponseMessage);
    }

    public function options()
    {
       throw new \Exception ($this->errorResponseMessage);
    }
    public function getList()
    {
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

    /* public getAppTable()
    /**
     * getAppTable
     * Pull object from the service manager
     * 
     * @access public
     * @return object (instance of the AppTable class)
     */
    public function getAppTable()
    {
        if (!$this->appTable) {
          $sm = $this->getServiceLocator();
          $this->appTable = $sm->get('Roles\Model\Apikeys\AppTable');
        }
        return $this->appTable;
    }

    /* public getClientTable()
    /**
     * getClientTable
     * 
     * @access public
     * @return object (instance of the ClientTable class)

     */
    public function getClientTable()
    {
        if (!$this->clientTable) {
          $sm = $this->getServiceLocator();
          $this->clientTable = $sm->get('Roles\Model\Apikeys\ClientTable');
        }
        return $this->clientTable;
    }

    /* public getClientAppTable()
    /**
     * getClientAppTable
     * 
     * @access public
     * @return  object (instance of the ClientAppTable class)

     */
    public function getClientAppTable()
    {
        if (!$this->clientAppTable) {
          $sm = $this->getServiceLocator();
          $this->clientAppTable = $sm->get('Roles\Model\Apikeys\ClientAppTable');
        }
        return $this->clientAppTable;
    }

    protected function getIdentifier($routeMatch, $request)
    {
       return $routeMatch->getParams();
    }

    protected function getLdap()
    {
        return $this->getServiceLocator()->get('Roles\Model\Ldap');
    }

}
