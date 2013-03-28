<?php
namespace RolesTest\Model\Apikeys;
use Roles\Model\Apikeys\Client;
use PHPUnit_Framework_TestCase;

class ClientTest extends PHPUnit_Framework_TestCase
{
    public function testClientInitialState()
    {
        $app = new Client();

        $this->assertNull($app->id, '"id", should initially be null');
        $this->assertNull($app->apikey, '"apikey", should initially be null');
        $this->assertNull($app->client_name, '"client_name", should initially be null');
        $this->assertNull($app->issued_date, '"issued_date", should initially be null');
        $this->assertNull($app->expiration_date, '"expiration_date", should initially be null');
        $this->assertNull($app->status, '"status", should initially be null');
        $this->assertNull($app->description, '"description", should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $app = new Client();
        $data  = array('id' => 123,
                       'apikey'     => 'ALDAFKLA938A383NN392',
                       'client_name'  => 'Name of client app',
                       'issued_date'  => '0000-00-00 00:00:00',
                       'expiration_date'  => '0000-00-00 00:00:00',
                       'status' => 0,
                       'description' => 'some kind of description'
                     );

        $app->exchangeArray($data);

        $this->assertSame($data['id'], $app->id, '"id" was not set correctly');
        $this->assertSame($data['apikey'], $app->apikey, '"apikey" was not set correctly');
        $this->assertSame($data['client_name'], $app->client_name, '"client_name" was not set correctly');
        $this->assertSame($data['issued_date'], $app->issued_date, '"issued_date" was not set correctly');
        $this->assertSame($data['expiration_date'], $app->expiration_date, '"expiration_date" was not set correctly');
        $this->assertSame($data['status'], $app->status, '"status" was not set correctly');
        $this->assertSame($data['description'], $app->description, '"description" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $app = new Client();

        $app->exchangeArray(array('id' => 123,
                       'apikey'     => 345,
                       'client_name'  => 'some client name',
                       'issued_date'  => '0000-00-00 00:00:00',
                       'expiration_date'  => '0000-00-00 00:00:00',
                       'status'           => 0,
                       'description'  => 'some description'
                     )
        );

        $app->exchangeArray(array());

        $this->assertNull($app->id, '"id" should have defaulted to null');
        $this->assertNull($app->apikey, '"apikey" should have defaulted to null');
        $this->assertNull($app->client_name, '"client_name" should have defaulted to null');
        $this->assertNull($app->issued_date, '"issued_date" should have defaulted to 0000-00-00 00:00:00');
        $this->assertNull($app->expiration_date, '"expiration_date" should have defaulted to 0000-00-00 00:00:00');
        $this->assertNull($app->status, '"status" should have defaulted to 0');
        $this->assertNull($app->description, '"description" should have defaulted to null');
    }

}
