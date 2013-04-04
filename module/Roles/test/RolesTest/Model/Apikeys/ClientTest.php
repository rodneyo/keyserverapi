<?php
namespace RolesTest\Model\Apikeys;

use Roles\Model\Apikeys\Client;
use Roles\Model\Apikeys\ClientTable;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class ClientTest extends PHPUnit_Framework_TestCase
{
    protected $client;
    protected $selectWith;
    protected $data = array('id' => 123,
                          'apikey' => 'N2MzNjc3MjNjZDA4MjUyYzJhNjFjZGEx',
                          'client_name' => 'Client X',
                          'issued_date' => '0000-00-00 00:00:00',
                          'expiration_date' => '0000-00-00 00:00:00',
                          'enabled' => 1,
                          'description' => 'Acme API'
                        );

    public function testClientInitialState()
    {
        $client = new Client();

        $this->assertNull($client->id, '"id", should initially be null');
        $this->assertNull($client->apikey, '"apikey", should initially be null');
        $this->assertNull($client->client_name, '"client_name", should initially be null');
        $this->assertNull($client->issued_date, '"issued_date", should initially be null');
        $this->assertNull($client->expiration_date, '"expiration_date", should initially be null');
        $this->assertNull($client->enabled, '"enabled", should initially be null');
        $this->assertNull($client->description, '"description", should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $client = new Client();
        $data  = array('id' => 123,
                       'apikey'     => 'ALDAFKLA938A383NN392',
                       'client_name'  => 'Name of client app',
                       'issued_date'  => '0000-00-00 00:00:00',
                       'expiration_date'  => '0000-00-00 00:00:00',
                       'enabled' => 0,
                       'description' => 'some kind of description'
                     );

        $client->exchangeArray($data);

        $this->assertSame($data['id'], $client->id, '"id" was not set correctly');
        $this->assertSame($data['apikey'], $client->apikey, '"apikey" was not set correctly');
        $this->assertSame($data['client_name'], $client->client_name, '"client_name" was not set correctly');
        $this->assertSame($data['issued_date'], $client->issued_date, '"issued_date" was not set correctly');
        $this->assertSame($data['expiration_date'], $client->expiration_date, '"expiration_date" was not set correctly');
        $this->assertSame($data['enabled'], $client->enabled, '"enabled" was not set correctly');
        $this->assertSame($data['description'], $client->description, '"description" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $client = new Client();

        $client->exchangeArray(array('id' => 123,
                       'apikey'     => 345,
                       'client_name'  => 'some client name',
                       'issued_date'  => '0000-00-00 00:00:00',
                       'expiration_date'  => '0000-00-00 00:00:00',
                       'enabled'           => 0,
                       'description'  => 'some description'
                     )
        );

        $client->exchangeArray(array());

        $this->assertNull($client->id, '"id" should have defaulted to null');
        $this->assertNull($client->apikey, '"apikey" should have defaulted to null');
        $this->assertNull($client->client_name, '"client_name" should have defaulted to null');
        $this->assertNull($client->issued_date, '"issued_date" should have defaulted to 0000-00-00 00:00:00');
        $this->assertNull($client->expiration_date, '"expiration_date" should have defaulted to 0000-00-00 00:00:00');
        $this->assertNull($client->enabled, '"enabled" should have defaulted to 0');
        $this->assertNull($client->description, '"description" should have defaulted to null');
    }

    public function testFetchAllReturnsAllClients()
    {
        $resultSet = new ResultSet();
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                   array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with()
                         ->will($this->returnValue($resultSet));

        $clientTable = new ClientTable($mockTableGateway);

        //test that both variables reference the same object.  A resultSet object in this case
        $this->assertSame($resultSet, $clientTable->fetchAll());
    }

    protected function setUpClient()
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Client());
        $resultSet->initialize(array($this->client));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                            array('select'),
                                            array(),
                                            '',
                                            false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with($this->selectWith)
                         ->will($this->returnValue($resultSet));

        $clientTable = new ClientTable($mockTableGateway);
        return $clientTable;
    }

}
