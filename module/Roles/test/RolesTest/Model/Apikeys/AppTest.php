<?php
namespace RolesTest\Model\Apikeys;
use Roles\Model\Apikeys\App;
use PHPUnit_Framework_TestCase;

class AppTest extends PHPUnit_Framework_TestCase
{
    public function testAppInitialState()
    {
        $app = new App();

        $this->assertNull($app->id, '"id", should initially be null');
        $this->assertNull($app->client_id, '"client_id", should initially be null');
        $this->assertNull($app->app_name, '"app_name", should initially be null');
        $this->assertNull($app->status, '"status", should initially be null');
        $this->assertNull($app->description, '"description", should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $app = new App();
        $data  = array('id' => 123,
                       'client_id'     => 345,
                       'app_name'  => 'some app name in AD',
                       'status'  => 0,
                       'description'  => 'some description'
                     );

        $app->exchangeArray($data);

        $this->assertSame($data['id'], $app->id, '"id" was not set correctly');
        $this->assertSame($data['client_id'], $app->client_id, '"client_id" was not set correctly');
        $this->assertSame($data['app_name'], $app->app_name, '"app_name" was not set correctly');
        $this->assertSame($data['status'], $app->status, '"status" was not set correctly');
        $this->assertSame($data['description'], $app->description, '"description" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $app = new App();

        $app->exchangeArray(array('id' => 123,
                       'client_id'     => 345,
                       'app_name'  => 'some app name in AD',
                       'status'  => 0,
                       'description'  => 'some description'
                     )
        );

        $app->exchangeArray(array());

        $this->assertNull($app->id, '"id" should have defaulted to null');
        $this->assertNull($app->client_id, '"client_id" should have defaulted to null');
        $this->assertNull($app->app_name, '"app_name" should have defaulted to null');
        $this->assertNull($app->status, '"status" should have defaulted to 0');
        $this->assertNull($app->description, '"description" should have defaulted to null');
    }

}
