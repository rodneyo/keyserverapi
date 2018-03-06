<?php
namespace Roles\Controller;

use Roles\Model\RollUp\RollUpStoredProcedure;

class AllUsersController extends ApiBaseController
{
    protected $rollUpStoredProcedure;

    public function __construct(RollUpStoredProcedure $rollUpDbProcedure)
    {
        $this->rollUpStoredProcedure = $rollUpDbProcedure;
    }

    /**
     * @param mixed $data
     *
     * @return mixed|zend
     * @throws \Exception
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

    /**
     * @return array
     * @throws \Exception
     */
    public function getAllUsers()
    {
        return $this->rollUpStoredProcedure->getAllUsers();
    }
}
