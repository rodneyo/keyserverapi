<?php
namespace Roles\Controller;

class AllUsersController extends ApiBaseController
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

    public function getAllUsers()
    {
        if (!$this->rollUpStoredProcedure) {
          $sm = $this->getServiceLocator();
          $this->rollUpStoredProcedure = $sm->get('RollUpStoredProcedure');
          return $this->rollUpStoredProcedure->getAllUsers();
        }
    }
}
