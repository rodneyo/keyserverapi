<?php
namespace Roles\Controller;

use Zend\View\Model\JsonModel;

class ApproversController extends ApiBaseController
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

            $data = array($this->getApproversByLocation($data['location']));
        }
        catch (\Exception $e) {
          $logData = $e->getMessage() . ':' . $e->getFile() . ':' . $e->getCode() . ':' 
            . print_r($data,true);
            $this->getServiceLocator()->get('Zend\Log')->crit($logData); 
            throw new \Exception($e->getMessage());
        }

        return $this->getJson($data);
    }

    public function getApproversByLocation($location)
    {
        if (!$this->rollUpStoredProcedure) {
          $sm = $this->getServiceLocator();
          $this->rollUpStoredProcedure = $sm->get('RollUpStoredProcedure');
          return $this->rollUpStoredProcedure->getApproversByLocationId($location);
        }
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

    protected function getIdentifier($routeMatch, $request)
    {
       return $routeMatch->getParams();
    }
}
