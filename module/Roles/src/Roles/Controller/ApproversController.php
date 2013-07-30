<?php
namespace Roles\Controller;

class ApproversController extends ApiBaseController
{
    protected $rollUpStoredProcedure;
    protected $ldap;
    protected $approverFilter;

    public function __construct($ldap, $rollUpStoredProcedure, $filter)
    {
        $this->rollUpStoredProcedure = $rollUpStoredProcedure;
        $this->ldap = $ldap;
        $this->approverFilter = $filter;
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

            $approvers = $this->getApproversByLocation($data['location']);
            $filteredApprovers = $this->ldap->filterApproversByGroup($approvers, $this->approverFilter);
    
            //temporary fix to get the array back into the previous format for the DIPS caller
            foreach ($filteredApprovers as $approver) {
              $userName['username'][] = $approver['username'];
              $userEmail['displayname'][] = $approver['displayname'];
            }
            $approversArray[] = array_merge($userName, $userEmail);
        }
        catch (\Exception $e) {
          $logData = $e->getMessage() . ':' . $e->getFile() . ':' . $e->getCode() . ':' 
            . print_r($data,true);
            $this->getServiceLocator()->get('Zend\Log')->crit($logData); 
            throw new \Exception($e->getMessage());
        }

        return $this->getJson($approversArray);
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
