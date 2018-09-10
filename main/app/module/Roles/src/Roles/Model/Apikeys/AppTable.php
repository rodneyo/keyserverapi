<?php
namespace Roles\Model\Apikeys;
use Zend\Db\TableGateway\TableGateway;

class AppTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /*
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
    }*/
}

