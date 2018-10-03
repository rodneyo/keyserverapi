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
          'uname' => 'jprou', 
          'appname' => 'dips'
        ));
        $this->request->getHeaders()->addHeaders(array(
          'x-stonemorapi' => 'N2MzNjc3MjNjZDA4MjUyYzJhNjFjZGEx'
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
    }

    public function testCanGetRolesAndLocations()
    {
    }

    public function getLdap()
    {
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage application is disabled for client or does not exist
     */
    public function testGetRolesReturnJSONExceptionOnError()
    {
        $this->routeMatch->setParam('appname', 'xxx');

          $this->request->setMethod('GET');
          $result = $this->controller->dispatch($this->request);
    }
}
