<?php
namespace Roles\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Roles\Model\Ldap;
use Roles\Model\Apikeys\AppTable;
use Roles\Model\Apikeys\ClientTable;

class RolesController extends AbstractRestfulController
{
    protected $appTable;
    protected $clientTable;
    protected $errorResponseMessage = 'Method Not Supported';

    public function __construct()
    {
      // register a custom handler for roles
      $rolesHandler = array($this, 'getRoles');
      $this->addHttpMethodHandler('get', $rolesHandler);
      //$this->getClientTable();

    }

    public function getAppTable()
    {
        if (!$this->appTable) {
          $sm = $this->getServiceLocator();
          $this->appTable = $sm->get('Roles\Model\Apikeys\AppTable');
        }
        return $this->appTable;
    }

    public function getClientTable()
    {
        if (!$this->clientTable) {
          $sm = $this->getServiceLocator();
          $this->clientTable = $sm->get('Roles\Model\Apikeys\ClientTable');
        }
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
        $this->getClientTable();
        /**
         * check api-key (apikey)
         * if valid query ad to get roles and locations
         *     uname, appname
         * Also check request timestamp against server to prevent replay
         */
        $clientApiKey = $this->getRequest()->getHeader('x-stonemorapi')->getFieldValue();
        try {
            $this->clientTable->validateApiKey($clientApiKey);

            $config = $this->getServiceLocator()->get('config');
            $ldap = new Ldap($config);

            $roles = $ldap->findRolesForUser($roleParams['uname'], $roleParams['appname']);
            $locations = $ldap->findLocationsForUser($roleParams['uname']);
            $data = array($roles, $locations);
        }
        catch (\Exception $e) {
            print('Error occurred: ' . $e->getMessage());
            // create error json 
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
       //return $this->methodNotAllowed();
    }

    public function delete($id)
    {
       throw new \Exception ($this->errorResponseMessage);
       //return $this->methodNotAllowed();
    }

    public function create($id)
    {
        throw new \Exception ($this->errorResponseMessage);
       //return $this->methodNotAllowed();
    }

    public function update($id, $data)
    {
       throw new \Exception ($this->errorResponseMessage);
       //return $this->methodNotAllowed();
    }

    public function options()
    {
       throw new \Exception ($this->errorResponseMessage);
       //return $this->methodNotAllowed();
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
