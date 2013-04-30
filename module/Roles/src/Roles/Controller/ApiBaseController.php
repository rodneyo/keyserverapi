<?php
namespace Roles\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * ApiBaseController 
 * 
 * @uses AbstractRestfulController
 * @author StoneMor Partners
 */
class ApiBaseController extends AbstractRestfulController
{
    protected $appTable;
    protected $clientTable;
    protected $clientAppTable;
    protected $errorResponseMessage = 'Method Not Supported';

    /* public isValidApiRequest($requestData)
    /**
     * isValidApiRequest
     * 
     * @param mixed $requestData 
     * @access public
     * @return void
     */
    public function isValidApiRequest($requestData)
    {
        $this->getClientTable();
        $clientId = $this->clientTable->hasValidApiKey($this->getRequest());

        $this->getClientAppTable();
        $this->clientAppTable->hasEnabledApp($clientId, $requestData['appname']);

        return $clientId;
    }

    /* public getClientAppTable()
    /**
     * getClientAppTable
     * 
     * @access public
     * @return  object (instance of the ClientAppTable class)
     * @TODO add to a factory and inject into controller

     */
    public function getClientAppTable()
    {
        if (!$this->clientAppTable) {
          $sm = $this->getServiceLocator();
          $this->clientAppTable = $sm->get('Roles\Model\Apikeys\ClientAppTable');
        }
        return $this->clientAppTable;
    }


    /* public getClientTable()
    /**
     * getClientTable
     * 
     * @access public
     * @return object (instance of the ClientTable class)
     * @TODO add to a factory and inject into controller

     */
    public function getClientTable()
    {
        if (!$this->clientTable) {
          $sm = $this->getServiceLocator();
          $this->clientTable = $sm->get('Roles\Model\Apikeys\ClientTable');
        }
        return $this->clientTable;
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


    /* public get($data)
    /**
     * get
     * 
     * @param mixed $data 
     * @access public
     * @return JSON
     */
    public function get($data) {}

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

    protected function getIdentifier($routeMatch, $request)
    {
       return $routeMatch->getParams();
    }
}
