<?php
namespace Roles\Controller;

class AllUsersController extends ApiBaseController
{
    protected $rollUpStoredProcedure;

    public function __construct($rollUpDbProcedure)
    {
        $this->rollUpStoredProcedure = $rollUpDbProcedure;
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

            $data = array($this->getAllUsers());
        }
        catch (\Exception $e) {
          $logData = $e->getMessage() . ':' . $e->getFile() . ':' . $e->getCode() . ':' 
            . print_r($data,true);
            $this->getServiceLocator()->get('Zend\Log')->crit($logData); 
            throw new \Exception($e->getMessage());
        }

        return $this->getJson($data);
    }

    /* public getAllUsers()
    /**
     * getAllUsers
     * 
     * @access public
     * @return void
     */
    public function getAllUsers()
    {
        return $this->rollUpStoredProcedure->getAllUsers();
    }
}
