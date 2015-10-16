<?php
namespace Roles\Model\RollUp;

/**
 * RollUpStoredProcedure 
 * 
 * @copyright Copyright (c) 2013 All rights reserved.
 * @author  StoneMor Parnterns
 * @TODO Create a factory to create the stored procedure calls and. This will reduce
 * duplication and allow the procedure names to be abstracted out into a config
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
     *
     * getLocationIdsByUser
     *
     * Return a list of location ids a user is assigned to
     * @param string $username 
     * @access public
     * @return array
     */
    //public function getLocationIdsByUser($username)
    public function getLocationIdsByUser($data)
    {
        $ctr = 0;
        $locations = array('locations' => array());
        $username = $data['uname'];
        $locationNames = [];

        try 
        {
           $statement = $this->rollUpDbAdapter->createStatement('call GetLocationsByUser(?)', array($username));
           $results = $statement->execute();
           foreach ($results as $result)
           {
             $locations['locations'][$ctr] = trim($result['location_code']);
             $locationNames[] = trim($result['location_name']);
             $ctr++;
           }

            if (array_key_exists('locnames', $data)) {
                $locations['names'] = $locationNames;
            }

           return $locations;
        }
        catch (\Exception $e)
        {
            $this->appLogger->crit($e);
            throw new \Exception($this->appErrorMessages['getLocationsByUser']);
        }
    }

    /* public getApproversByLocationId($location)
    /**
     * getApproversByLocationId
     * 
     * @param mixed $location 
     * @access public
     * @return array
     */
    public function getApproversByLocationId($location)
    {
        $ctr = 0;
        $approvers = array();

        try 
        {
          $statement = $this->rollUpDbAdapter->createStatement('call GetApprovers(?)', array($location));
          $results = $statement->execute();
          foreach ($results as $row)
          {
            $approvers[$ctr] = array('username' => trim($row['user_profile']),
                                     'displayname' => trim($row['display_name'])
                                    );
            $ctr++;
          }
          return $approvers;
        }
        catch (\Exception $e)
        {
            $this->appLogger->crit($e);
            throw new \Exception($this->appErrorMessages['getApproversByLocation']);
        }
    }

    /* public getAllUsers()
    /**
     * getAllUsers
     * 
     * @access public
     * @return array
     */
    public function getAllUsers()
    {
        $ctr = 0;

        $displayName = array('displayname' => array());
        $userName = array('username' => array());
        $email = array('email' => array());

        try 
        {
          $statement = $this->rollUpDbAdapter->createStatement('call GetAllUsers');
          $results = $statement->execute();
          foreach ($results as $row)
          {
            $userName['username'][$ctr] = trim($row['user_profile']);
            $displayName['displayname'][$ctr] = trim($row['display_name']);
            $email['email'][$ctr] = trim($row['email']);
            $ctr++;
          }
          return array_merge($userName, $displayName, $email);
        }
        catch (\Exception $e)
        {
            $this->appLogger->crit($e);
            throw new \Exception($this->appErrorMessages['getApproversByLocation']);
        }
    }
}
