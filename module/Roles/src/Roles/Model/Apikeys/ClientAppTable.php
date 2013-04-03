<?php
namespace Roles\Model\Apikeys;
use Zend\Db\TableGateway\TableGateway;

/**
 * ClientAppTable 
 * 
 * @author  StoneMor Partners
 */
class ClientAppTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /* public fetchAll()
    /**
     * fetchAll
     * 
     * @access public
     * @return object
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /* public getAppByClientId($id)
    /**
     * getAppByClientId
     * 
     * @param mixed $id 
     * @access public
     * @return object
     */
    public function getAppByClientId($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('client_id' => $id));
        $row = $rowset->current();

        if (!$row->client_id) {
          throw new \Exception ('App does not exist');
        } elseif (!$row->status) {
          throw new \Exception ('App is disabled for the calling client');
        }

        return $row;
    }

    /* public hasEnabledApp($clientId, $appName)
    /**
     * hasEnabledApp
     * 
     * @param mixed $clientId 
     * @param mixed $appName 
     * @access public
     * @return object
     */
    public function hasEnabledApp($clientId, $appName)
    {
        $clientId = (int) $clientId;
        $appName = (string) $appName;

        $sqlSelect = $this->tableGateway->getSql()->select();

        $sqlSelect->where(array('client_id = ?' => $clientId))
                  ->where(array('enabled = ?' => true))
                  ->where(array('name = ? ' => $appName))
                  ->join('app', 'app.id = app_id', array('name'));


        $rowSet = $this->tableGateway->selectWith($sqlSelect);

        if ($rowSet->count() <= 0) {
            throw new \Exception ('App is disabled or does not exist for the calling client');
        }

    }
}

