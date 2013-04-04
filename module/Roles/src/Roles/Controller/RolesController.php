<?php
namespace Roles\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Roles\Model\Ldap;
use Roles\Model\Apikeys\AppTable;
use Roles\Model\Apikeys\ClientTable;
use Roles\Model\Apikeys\ClientAppTable;

class RolesController extends AbstractRestfulController
{
    protected $appTable;
    protected $clientTable;
    protected $clientAppTable;
    protected $errorResponseMessage = 'Method Not Supported';

    public function __construct()
    {
      // register a custom handler for roles
      $rolesHandler = array($this, 'getRoles');
      $this->addHttpMethodHandler('get', $rolesHandler);

    }

    /* public getAppTable()
    /**
     * getAppTable
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

    /* public getRoles($mvcEvent)
    /**
     * getRoles
     * 
     * @param  object $mvcEvent 
     * @access public
     * @return json
     */
    public function getRoles($mvcEvent)
    {
        $roleParams = $this->getIdentifier($mvcEvent->getRouteMatch(), $mvcEvent->getRequest());
        //@TODO Check header request time.  request time must be within 3 
        //minute before or after window on keyserver time.
        //
        $clientApiKey = $this->getRequest()->getHeader('x-stonemorapi')->getFieldValue();
        try {
            $this->getClientTable();

            $clientId = $this->clientTable->hasValidApiKey($clientApiKey);

            $this->getClientAppTable();
            $this->clientAppTable->hasEnabledApp($clientId, $roleParams['appname']);

            $config = $this->getServiceLocator()->get('config');
            $ldap = new Ldap($config);

            $roles = $ldap->findRolesForUser($roleParams['uname'], $roleParams['appname']);
            $locations = $ldap->findLocationsForUser($roleParams['uname']);
            $data = array($roles, $locations);
        }
        catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this->getJson($data);
    }

    protected function getIdentifier($routeMatch, $request)
    {
       return $routeMatch->getParams();
    }

    public function getList()
    {
    }

    public function get($data)
    {
    }

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

    /* protected getJson($data)
     * getJson
     * 
     * @param mixed $data 
     * @access protected
     * @return zend JsonModel
     */
    protected function getJson($data)
    {
      return new JsonModel($data);
    }
}
