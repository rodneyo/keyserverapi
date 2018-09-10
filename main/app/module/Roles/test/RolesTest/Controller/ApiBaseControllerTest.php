<?php
namespace RolesTest\Controller;

use RolesTest\Bootstrap;
use Roles\Controller\ApiBaseController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Http\Headers;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Ldap\Ldap;
use PHPUnit_Framework_TestCase;

class ApiBaseControllerTest extends PHPUnit_Framework_TestCase
{
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;
    protected $ldapOptions;
    protected $abstractApiBase;
    protected $fixture;
 
    protected function setUp()
    {
      $this->fixture = 
        array(
          'data' => array(
             'appname'=>'dips',
             'uname'=>'jprou'
          ),
          'header' => array(
             'x-stonemorapi' => 'N2MzNjc3MjNjZDA4MjUyYzJhNjFjZGEx'
          )
        );

        $serviceManager = Bootstrap::getServiceManager();
        $this->abstractApiBase = $this->getMockForAbstractClass('Roles\Controller\ApiBaseController');

        $this->request    = new Request();

        $this->routeMatch = new RouteMatch($this->fixture['data']);
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');

        $this->request->getHeaders()->addHeaders($this->fixture['header']);

        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        $this->ldapOptions = $config;
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->abstractApiBase->setEvent($this->event);
        $this->abstractApiBase->setServiceLocator($serviceManager);
    }

    /* public testIsValidApiRequest()
    /**
     * testIsValidApiRequest
     * 
     * @access public
     * @return int 
     */
    public function testIsValidApiRequest()
    {
       $this->abstractApiBase->dispatch($this->request);
       $this->assertGreaterThanOrEqual(1,
       $this->abstractApiBase->isValidApiRequest($this->fixture['data'])
       ); 
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage api key expired or does not exist:
     */
    public function testHasInvalidApiKey()
    {
        $header = $this->request->getHeaders('x-stonemorapi');
        $header->setFieldValue('');
       $this->abstractApiBase->dispatch($this->request);
       
       $this->abstractApiBase->isValidApiRequest($this->fixture['data']);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Method Not Supported
     */
    public function testApiPostIsInvalid()
    {
        $this->request->setMethod('POST');
        $this->assertTrue($this->abstractApiBase->dispatch($this->request));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Method Not Supported
     */
    public function testApiPutIsInvalid()
    {
        $this->request->setMethod('PUT');
        $this->assertTrue($this->abstractApiBase->dispatch($this->request));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Method Not Supported
     */
    public function testApiDeleteIsInvalid()
    {
        $this->request->setMethod('DELETE');
        $this->assertTrue($this->abstractApiBase->dispatch($this->request));
    }
}
