<?php
namespace Roles\Controller;

use Roles\Model\Ldap;
use Roles\Model\RollUp\RollUpStoredProcedure;

class RolesController extends ApiBaseController
{
    protected $rollUpStoredProcedure;
    protected $ldap;

    public function __construct(Ldap $ldap, RollUpStoredProcedure $rollUpDbProcedure)
    {
        $this->rollUpStoredProcedure = $rollUpDbProcedure;
        $this->ldap = $ldap;
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
            $data = $this->detectRunTimeEnvironment($data);
            $roles = $this->ldap->findRolesForUser($data['uname'], $data['appname']);

            $locations = $this->getLocationIdsByUser($data);
            $hasAllLocations = $this->checkAllLocations($data);

            // Variable $data['aloc'] is an optional parameter in the route to this controller.
            // Its purpose is to flag whether or not the all_locations key/value should be sent via JSON.
            if ($hasAllLocations["all_locations"] === "1" && isset($data['aloc'])) {
                $all_locations = array("all_locations" => true);
                $approvers = array($roles, $locations, $all_locations);
            }
            else {
                $approvers = array($roles, $locations);
            }

        }
        catch (\Exception $e) {
          $logData = $e->getMessage() . ':' . $e->getFile() . ':' . $e->getCode() . ':'
            . print_r($data,true);
            $this->getServiceLocator()->get('Zend\Log')->crit($logData);
            throw new \Exception($e->getMessage());
        }

        // Until we get a separate domain controller for AD we have to do this tacky hack
        // to remove the prepended app name from the role before sending it back.
        $approvers = $this->removeAdAppPrefix($approvers, $data['appname']);

        return $this->getJson($approvers);
    }

    /**
     * @param $data
     *
     * @return array
     * @throws \Exception
     */
    public function getLocationIdsByUser($data)
    {
        return $this->rollUpStoredProcedure->getLocationIdsByUser($data);
    }

    /**
     * @param $data
     *
     * @return bool
     * @throws \Exception
     */
    public function checkAllLocations($data)
    {
        return $this->rollUpStoredProcedure->checkAllLocations($data);
    }
}
