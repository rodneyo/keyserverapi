<?php
namespace Roles\Controller;

use Zend\View\Model\JsonModel;

class ApproversController extends ApiBaseController
{
    protected $rollUpStoredProcedure;

    public function get($data)
    {
      return new JsonModel(array('location'=>'blah'));
      /*
        try {
            $this->isValidApiRequest($data);

            $data = array($this->getApproversByLocationId($data['location']));
        }
        catch (\Exception $e) {
          $logData = $e->getMessage() . ':' . $e->getFile() . ':' . $e->getCode() . ':' 
            . print_r($data,true);
            $this->getServiceLocator()->get('Zend\Log')->crit($logData); 
            throw new \Exception($e->getMessage());
        }

        return $this->getJson($data);
       */
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

    public function getApproversByLocationId($location)
    {
        if (!$this->rollUpStoredProcedure) {
            $sm = $this->getServiceLocator();
            $this->rollUpStoredProcedure = $sm->get('RollUpStoredProcedure');
            return $this->rollUpStoredProcedure->getApproversByLocationId($location);
        }
    }
}
