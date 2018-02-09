<?php
namespace Roles\Model\RollUp;

use Zend\Db\Adapter\Adapter;
use Zend\Log\Logger;

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


    /**
     * @param $dbAdapter
     * @param $appLogger
     * @param $appErrorMessages
     */
    public function __construct(Adapter $dbAdapter, Logger $appLogger, array $appErrorMessages) {
        $this->rollUpDbAdapter = $dbAdapter;
        $this->appLogger = $appLogger;
        $this->appErrorMessages = $appErrorMessages;
    }

    /**
     * Return a list of location ids a user is assigned to
     * @param $data
     *
     * @return array
     * @throws \Exception
     */
    public function getLocationIdsByUser($data)
    {
        $ctr = 0;
        $locations = array('locations' => array());
        $username = $data['uname'];
        $locationNames = array();

        try
        {
           $statement = $this->rollUpDbAdapter->createStatement('call GetLocationsByUser(?)', array($username));
           $results = $statement->execute();

           foreach ($results as $result)
           {
             $locations['locations'][$ctr] = trim($result['location_code']);
             $locationNames[trim($result['location_code'])] = trim($result['location_name']);
             $ctr++;
           }

            // load location names when requested
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

    /**
     * Return value of all_locations for a user
     * @param $data
     *
     * @return bool
     * @throws \Exception
     */
    public function checkAllLocations($data)
    {
        $username = $data['uname'];

        try
        {
            $statement = $this->rollUpDbAdapter->createStatement('SELECT all_locations FROM rollup.user_master WHERE user_profile = ?', array($username));
            $results = $statement->execute();
            $all_locations_field = $results->current();

            return $all_locations_field;

        }
        catch (\Exception $e)
        {
            $this->appLogger->crit($e);
            throw new \Exception($this->appErrorMessages['checkAllLocations']);
        }
    }

    /**
     * @param $location
     *
     * @return array
     * @throws \Exception
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

    /**
     * @return array
     * @throws \Exception
     */
    public function getAllLocations()
    {
        $locationData = [];
        try
        {
            $sql = "select distinct 
                    um.carlib_key, trim(um.unit_name)                                  as unit_name, 
                    trim(GetHigherLevelUnit(um.carlib_key,'Sales','SalesDivision'))    as sales_division,
                    trim(GetHigherLevelUser(um.carlib_key,'Sales','SalesDivision'))    as sales_division_user,
                    trim(GetHigherLevelUnit(um.carlib_key,'Sales','SalesRegion'))      as sales_region,
                    trim(GetHigherLevelCARLIBKey(um.carlib_key,'Sales','SalesRegion')) as sales_region_code,
                    trim(GetHigherLevelUser(um.carlib_key,'Sales','SalesRegion'))      as sales_region_user,
                    trim(GetHigherLevelUnit(um.carlib_key,'Sales','SalesArea'))        as sales_area,
                    trim(GetHigherLevelUser(um.carlib_key,'Sales','SalesArea'))        as sales_area_user,
                    trim(GetHigherLevelUnit(um.carlib_key,'Admin','AdminDivision'))    as admin_division,
                    trim(GetHigherLevelUser(um.carlib_key,'Admin','AdminDivision'))    as admin_division_user,
                    trim(GetHigherLevelUnit(um.carlib_key,'Admin','AdminRegion'))      as admin_region,
                    trim(GetHigherLevelUser(um.carlib_key,'Admin','AdminRegion'))      as admin_region_user,
                    trim(GetHigherLevelUnit(um.carlib_key,'Admin','AdminArea'))        as admin_area,
                    trim(GetHigherLevelUser(um.carlib_key,'Admin','AdminArea'))        as admin_area_user,
                    trim(GetHigherLevelUnit(um.carlib_key,'Maint','MaintDivision'))    as maint_division,
                    trim(GetHigherLevelUser(um.carlib_key,'Maint','MaintDivision'))    as maint_division_user,
                    trim(GetHigherLevelUnit(um.carlib_key,'Maint','MaintRegion'))      as maint_region,
                    trim(GetHigherLevelUser(um.carlib_key,'Maint','MaintRegion'))      as maint_region_user,
                    trim(GetHigherLevelUnit(um.carlib_key,'Maint','MaintArea'))        as maint_area,
                    trim(GetHigherLevelUser(um.carlib_key,'Maint','MaintArea'))        as maint_area_user,
                    trim(GetHigherLevelUnit(um.carlib_key,'Funeral','FHDivision'))     as fhmgmt_division,
                    trim(GetHigherLevelUser(um.carlib_key,'Funeral','FHDivision'))     as fhmgmt_division_user,
                    trim(GetHigherLevelUnit(um.carlib_key,'Funeral','FHRegion'))       as fhmgmt_region,
                    trim(GetHigherLevelUser(um.carlib_key,'Funeral','FHRegion'))       as fhmgmt_region_user,
                    trim(GetHigherLevelUnit(um.carlib_key,'Funeral','FHArea'))         as fhmgmt_area,
                    trim(GetHigherLevelUser(um.carlib_key,'Funeral','FHArea'))         as fhmgmt_area_user
                from
                    unit_master um 
                    join unit_type_master utm on um.unit_type_id = utm.id 
                where 
                    utm.unit_type = 'Location'
                order by
                    um.carlib_key
";
            $statement = $this->rollUpDbAdapter->createStatement($sql);
            $results = $statement->execute();
            foreach ($results as $row)
            {
                $higherUnits = array_slice($row, 1);

                $locationData[] = [
                    $row['carlib_key'] => [$higherUnits]
                ];
            }
            return $locationData;
        }
        catch (\Exception $e)
        {
            $this->appLogger->crit($e);
            throw new \Exception($this->appErrorMessages['getAllLocations']);
        }
    }


    /**
     * @return array
     * @throws \Exception
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
