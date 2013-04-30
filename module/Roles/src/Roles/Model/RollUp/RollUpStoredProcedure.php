<?php
namespace Roles\Model\RollUp;

/**
 * RollUpStoredProcedure 
 * 
 * @copyright Copyright (c) 2013 All rights reserved.
 * @author  StoneMor Parnterns
 */
class  RollUpStoredProcedure
{
    protected $rollUpDbAdapter;
    protected $appLogger;
    protected $appErrorMessages;

    /* public __construct($dbAdapter, $appLogger)
    /**
     * __construct
     * 
     * @param object $dbAdapter  (injected from sm)
     * @param object $appLogger (injected from sm)
     * @access public
     * @return void
     */
    public function __construct($dbAdapter, $appLogger, $appErrorMessages)
    {
        $this->rollUpDbAdapter = $dbAdapter;
        $this->appLogger = $appLogger;
        $this->appErrorMessages = $appErrorMessages;
    }

    /* public getLocationIdsByUser($username)
    /**
     * getLocationIdsByUser
     *
     * Return a list of location ids a user is assigned to
     * @param string $username 
     * @access public
     * @return array
     */
    public function getLocationIdsByUser($username)
    {
        $ctr = 0;
        $locations = array('locations' => array());
        try 
        {
           $results = $this->rollUpDbAdapter->query('call GetLocationsByUser(?)', array($username));
           foreach ($results as $result)
           {
             $locations['locations'][$ctr] = $result['location_code'];
             $ctr++;
           }
           return $locations;
        }
        catch (\Exception $e)
        {
            $this->appLogger->crit($e);
            throw new \Exception($this->appErrorMessages['getLocationsByUser']);
        }
    }

    public function getApproversByLocationId($location)
    {
        try 
        {
          $results = $this->rollUpDbAdapter->query('call GetApprovers(?)', array($location));
        }
        catch (\Exception $e)
        {
            $this->appLogger->crit($e);
            throw new \Exception($this->appErrorMessages['getApproversByLocation']);
        }
    }
}
