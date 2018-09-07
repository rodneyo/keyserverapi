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
abstract class ApiBaseController extends AbstractRestfulController
{
    protected $appTable;
    protected $clientTable;
    protected $clientAppTable;
    protected $errorResponseMessage = 'Method Not Supported';
    public    $testEnvironment = False;

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

    /**
     * Append a -test to the end of the app name to find the
     * appropriate application OU in AD
     * @param $urlParams
     *
     * @return mixed
     */
    public function detectRunTimeEnvironment($urlParams)
    {
      if (array_key_exists('APPLICATION_ENV', $_SERVER)) {
        switch ($_SERVER['APPLICATION_ENV']) {
          case 'testing':
          case 'dev':
          case 'development':
            $urlParams['appname'] .= '-test';
            $this->testEnvironment = True;
            break;

          case 'production':
            $urlParams['appname'] .= '-PROD';
            $this->testEnvironment = False;
            break;

        }
      }
      return $urlParams;
    }

    /**
     * removeAdAppPrefix
     * 
     * @param array $checkData 
     * @param string $appname 
     * @access public
     * @return array
     */
    public function removeAdAppPrefix(array $checkData, $appname='')
    {
        foreach ($checkData[0] as $key=>$value) {
            $regex = '/' .  $appname . '\s*/i';
            $checkData[0][$key] = preg_replace($regex, '', $value);
        }
		return $checkData;
    }

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

    /**
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
