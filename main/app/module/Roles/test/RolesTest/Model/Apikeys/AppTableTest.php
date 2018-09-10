<?php
namespace RolesTest\Model\Apikeys;

use Roles\Model\Apikeys\App;
use Roles\Model\Apikeys\AppTable;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class AppTableTest extends PHPUnit_Framework_TestCase
{
    protected $app;
    protected $data = array('id' => 123,
                        'client_id'     => 345,
                        'app_name'  => 'some app name in AD under application security',
                        'status'  => 1,
                        'description'  => 'description of app'
                     );

    public function testFetchAllReturnsAllApps()
    {
        $resultSet = new ResultSet();
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                   array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with()
                         ->will($this->returnValue($resultSet));

        $appTable = new AppTable($mockTableGateway);

        //test that both variables reference the same object.  A resultSet object in this case
        $this->assertSame($resultSet, $appTable->fetchAll());
    }

    /*
    public function testCanFindAppByClientId()
    {
        $this->app = new App();
        $this->app->exchangeArray($this->data);

        $appTable = $this->setUpAppTable();
        $this->assertSame($this->app, $appTable->getAppByClientId(345), 'Can not find retrieve app by client_id');
    }
     */

    /*
    public function testExceptionThrownWhenAppStatusIsDisabled()
    {
        $this->data['status'] = 0; 

        $this->app = new App();
        $this->app->exchangeArray($this->data);

        $appTable = $this->setUpAppTable();
        try {
            $appTable->getAppByClientId(345);
        } 
        catch (\Exception $e) {
            $this->assertSame('App is disabled for the calling client', $e->getMessage());
            return;
        }

        $this->fail('Expected exception was not thrown');

    }
     */

    /*
    public function testExceptionThrownWhenAppDoesNotExist()
    {
        $this->app = array();
        $appTable = $this->setUpAppTable();

        try {
            $appTable->getAppByClientId(345);
        }
        catch (\Exception $e) {
            $this->assertSame('App does not exist', $e->getMessage());
            return;
        }

        $this->fail('Expected exception not thrown when app does not exist');
    }
     */

    protected function setUpAppTable()
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new App());
        $resultSet->initialize(array($this->app));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', 
                                           array('select'),
                                           array(),
                                           '',
                                           false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('client_id' => $this->data['client_id']))
                         ->will($this->returnValue($resultSet));

        $appTable = new AppTable($mockTableGateway);

        return $appTable;
    }
}
