<?php
namespace Roles\Controller;

use Roles\Model\RollUp\RollUpStoredProcedure;

class AllLocationsController extends ApiBaseController
{
    protected $rollUpStoredProcedure;
    protected $ldap;
    protected $data;

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
            $this->data =  $this->rollUpStoredProcedure->getAllLocations();
        }
        catch (\Exception $e) {
            $logData = $e->getMessage() . ':' . $e->getFile() . ':' . $e->getCode();
            $this->getServiceLocator()->get('Zend\Log')->crit($logData);
            throw new \Exception($e->getMessage());
        }
        return $this->getJson($this->data);
    }


    public function createCustomJSON($data)
    {
        foreach ($data as $items) {
            foreach ($items as $key => $value) {
                if ($key == 'unit_name') {
                    list($locNbr, $locName) = preg_split('/-/', $value);
                }
                $jsonArray['CorpstructData'][] = [
                    $key => $value
                ];
            }
        }
    }
}
