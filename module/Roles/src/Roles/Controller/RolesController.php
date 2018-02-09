<?php
namespace Roles\Controller;

use Zend\View\Model\JsonModel;

class RolesController extends ApiBaseController
{
    protected $rollUpStoredProcedure;
    protected $ldap;

    public function __construct($ldap, $rollUpDbProcedure)
    {
        $this->rollUpStoredProcedure = $rollUpDbProcedure;
        $this->ldap = $ldap;
    }

    /**
     * @param mixed $data
     *
     * @return JsonModel
     * @throws \Exception
     */
    public function get($data)
    {
        try {
            $this->isValidApiRequest($data);
            $data = $this->detectRunTimeEnvironment($data);
            // Get roles for user
            $roles = $this->ldap->findRolesForUser($data['uname'], $data['appname']);

            // Get locations for user
            $locations = $this->getLocationIdsByUser($data);
            // Check if user has all locations
            $hasAllLocations = $this->checkAllLocations($data);

            // Variable $data['aloc'] is an optional parameter in the route to this controller.
            // Its purpose is to flag whether or not the all_locations key/value should be sent via JSON.
            if ($hasAllLocations["all_locations"] === "1" && isset($data['aloc'])) {
                // If user has all locations...
                // Set variable to true...
                $all_locations = array("all_locations" => true);
                // ...and include all_locations in JSON
                $approvers = array($roles, $locations, $all_locations);
            }
            else {
                // $all_locations = array("all_locations" => false);
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
     * @param array $data
     *
     * @return mixed
     * @TODO add to a factory and inject into controller
     */
    public function getLocationIdsByUser($data)
    {
        return $this->rollUpStoredProcedure->getLocationIdsByUser($data);
    }

    /**
     * @param array $data
     *
     * @return boolean
     * @TODO add to a factory and inject into controller
     */
    public function checkAllLocations($data)
    {
        return $this->rollUpStoredProcedure->checkAllLocations($data);
    }
}
