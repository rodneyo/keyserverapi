<?php
namespace Dips\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Dips\Model\Ldap;

class RolesController extends AbstractRestfulController
{
    protected $allowedHttpMethods = array('GET');
    protected $errorResponseCode = '501';
    protected $jsonErrorResponse = array(
                                  'error' => array(
                                    'responseCode' => '501',
                                    'responseMsg' => 'Method not supported'
                                  )
                                );
    public function __construct()
    {
      // register a custom handler for roles
      $rolesHandler = array($this, 'getRoles');
      $this->addHttpMethodHandler('get', $rolesHandler);

    }

    public function getRoles($mvcEvent)
    {
        $roleParams = $this->getIdentifier($mvcEvent->getRouteMatch(), $mvcEvent->getRequest());
        /**
         * check api-key (apikey)
         * if valid query ad to get roles and locations
         *     uname, appname
         */
      $config = $this->getServiceLocator()->get('config');
      $ldap = new Ldap($config);

      $roles = $ldap->findRolesForUser($roleParams['uname'], $roleParams['appname']);
      $locations = $ldap->findLocationsForUser($roleParams['uname']);
      $data = array($roles, $locations);

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
       return $this->methodNotAllowed();
    }

    public function delete($id)
    {
       return $this->methodNotAllowed();
    }

    public function create($id)
    {
       return $this->methodNotAllowed();
    }

    public function update($id, $data)
    {
       return $this->methodNotAllowed();
    }

    /* protected methodNotAllowed()
    /**
     * methodNotAllowed
     * 
     * @access protected
     * @return void
     */
    protected function methodNotAllowed()
    {
        if (!in_array($this->getRequest()->getMethod(), $this->allowedHttpMethods)) {
            $this->getResponse()->setStatusCode($this->errorResponseCode);
            return $this->getJson($this->jsonErrorResponse);
        } 
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