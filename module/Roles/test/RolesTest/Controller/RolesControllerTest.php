<?php
namespace RolesTest\Controller;
 
use RolesTest\Bootstrap;
use Roles\Controller\RolesController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Http\Headers;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Ldap\Ldap;
use PHPUnit_Framework_TestCase;
 
class RolesControllerTest extends PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;
    protected $ldapOptions;
 
    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new RolesController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array(
          'controller' => 'roles',
          'apikey' => '', 
          'uname' => '', 
          'appname' => ''
        ));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        $this->ldapOptions = $config;
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
        $this->request->getHeaders()->addHeaders(array(
          'x-stonemorapi' => 'N2MzNjc3MjNjZDA4MjUyYzJhNjFjZGEx'
        ));
    }
 
    public function testCanAPIBeAccessed()
    {
        $this->request->setMethod('GET');
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode()); 
    }

    public function testApiPostIsInvalid()
    {
        try {
            $this->request->setMethod('POST');
            $result = $this->controller->dispatch($this->request);
            $response = $this->controller->getResponse();
        }
        catch (\Exception $e) {
           $this->assertEquals('Method Not Supported', $e->getMessage()); 
           return;
        }

        $this->fail('Did not fail on POST to API');
    }

    public function testApiPutIsInvalid()
    {
        try {
            $this->request->setMethod('PUT');
            $result = $this->controller->dispatch($this->request);
            $response = $this->controller->getResponse();
        } 
        catch (\Exception $e) {
            $this->assertEquals('Method Not Supported', $e->getMessage()); 
        }
    }

    public function testApiDeleteIsInvalid()
    {
        try {
            $this->request->setMethod('DELETE');
            $result = $this->controller->dispatch($this->request);
            $response = $this->controller->getResponse();
        } 
        catch (\Exception $e) {
            $this->assertEquals('Method Not Supported', $e->getMessage());
        }
    }

    public function testGetAppTableReturnsInstanceOfAppTable()
    {
        $this->assertInstanceOf('Roles\Model\Apikeys\AppTable', $this->controller->getAppTable());
    }

    /* This should probably go into a Model test */
    public function testCanConnectToAd()
    {
        $ldap = new Ldap($this->ldapOptions['ldap']['server1']);
        $this->assertInstanceOf('Zend\Ldap\Ldap', $ldap, 'Not an instance of Zend Ldap');

        $ldap->bind($this->ldapOptions['client']['username'],
                    $this->ldapOptions['client']['password']
                   );
        $acctName = $ldap->getCanonicalAccountName($this->ldapOptions['client']['username']);
        $expectedAcctName = 'STONEMOR\\' . $this->ldapOptions['client']['username'];

        $this->assertTrue($acctName === $expectedAcctName, 'Could not bind to AD');
    }
}
