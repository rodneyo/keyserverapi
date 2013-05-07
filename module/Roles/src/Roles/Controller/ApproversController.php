<?php
namespace Roles\Controller;

class ApproversController extends ApiBaseController
{
    protected $rollUpStoredProcedure;

    public function __construct($rollUpStoredProcedure)
    {
        $this->rollUpStoredProcedure = $rollUpStoredProcedure;
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

    /* public getApproversByLocation($location)
    /**
     * getApproversByLocation
     * 
     * @param mixed $location 
     * @access public
     * @return array
     */
    public function getApproversByLocation($location)
    {
        return $this->rollUpStoredProcedure->getApproversByLocationId($location);
    }
}
