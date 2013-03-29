<?php
namespace Roles\Model\Apikeys;
use Zend\Db\TableGateway\TableGateway;
use Roles\ValidateApiKeyInterface;

class ClientTable implements ValidateApiKeyInterface
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

    public function getClientByApiKey($apikey)
    {
        $apikey = (string) $apikey;
        $rowset = $this->tableGateway->select(array('apikey' => $apikey));
        $row = $rowset->current();

        if (!$row) {
          throw new \Execption ('Client does not exist');
        }

        return $row;
    }

    public function validateApiKey($apikey)
    {
        /*
         * Does apikey exist for client
         * - Is apikey enabled
         * - Has the apikey expired
         * return true or false and
         * log true reason
         */
    }
}

