<?php
namespace Roles\Model\Apikeys;
use Zend\Db\TableGateway\TableGateway;

/**
 * ClientTable 
 * 
 * @author  StoneMor Parters
 */
class ClientTable
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

    /* public getClientByApiKey($apikey)
    /**
     * getClientByApiKey
     * 
     * @param mixed $apikey 
     * @access public
     * @return object
     */
    public function getClientByApiKey($apikey)
    {
         $apikey = (string) $apikey;
         $sqlSelect = $this->tableGateway->getSql()->select();
         $sqlSelect->where(array('apikey = ?' => $apikey))
                   ->join('client_app', 'client_app.client_id = client.id', array('enabled'), 'left')
                   ->join('app', 'app.id = client_app.app_id', array('name'), 'left');

         $rowSet = $this->tableGateway->selectWith($sqlSelect);

        if ($rowSet->count() <= 0) {
          throw new \Exception ('Client does not exist');
        }

        return $rowSet;
    }

    /* public hasValidApiKey($apikey)
    /**
     * hasValidApiKey
     * 
     * @param string $apikey 
     * @access public
     * @return int
     */
    public function hasValidApiKey($apikey)
    {
        $apikey = (string) $apikey;
        $currDateTime = new \Zend\Db\Sql\Expression("NOW()");
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->where(array('apikey = ?' => $apikey))
                  ->columns(array('id'))
                  ->where('expiration_date >= ' . $currDateTime->getExpression());

        $rowSet = $this->tableGateway->selectWith($sqlSelect);

        if ($rowSet->count() <= 0) {
            throw new \Exception ('Client does not exist or has expired');
        }
        return $rowSet->current()->id; 

    }
}

