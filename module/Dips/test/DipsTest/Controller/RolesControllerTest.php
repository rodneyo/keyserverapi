<?php
namespace DipsTest\Controller;
 
use DipsTest\Bootstrap;
use Dips\Controller\RolesController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use PHPUnit_Framework_TestCase;
 
class RolesControllerTest extends PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;
 
    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new RolesController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'roles'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }
 
    public function testCanAPIBeAccessed()
    {
      $result = $this->controller->dispatch($this->request);
      $response = $this->controller->getResponse();

      $this->assertEquals(200, $response->getStatusCode()); 
    }

    public function testApiPostIsInvalid()
    {
       $this->request->setMethod('POST');
       $result = $this->controller->dispatch($this->request);
       $response = $this->controller->getResponse();
       $this->assertEquals(501, $response->getStatusCode()); 
    }

    public function testApiPutIsInvalid()
    {
       $this->request->setMethod('PUT');
       $result = $this->controller->dispatch($this->request);
       $response = $this->controller->getResponse();
       $this->assertEquals(501, $response->getStatusCode()); 
    }

    public function testApiDeleteIsInvalid()
    {
       $this->request->setMethod('DELETE');
       $result = $this->controller->dispatch($this->request);
       $response = $this->controller->getResponse();
       $this->assertEquals(501, $response->getStatusCode()); 
    }

    public function testIsGetRolesRequestValid()
    {
       //$this->request->setMethod('GET');
       //$this->routeMatch->setParam('action', 'get-roles');
       $result = $this->controller->dispatch($this->request);
       $response = $this->controller->getResponse();

       $this->assertEquals(200, $response->getStatusCode()); 
       var_dump($response->getContent());
    }
}
