<?php
namespace Roles;
use Zend\Mvc\MvcEvent;
use Roles\Model\ApiDbServiceFactory;
//use Roles\Model\Apikeys\App;
use Roles\Model\Apikeys\AppTable;
//use Roles\Model\Apikeys\Client;
use Roles\Model\Apikeys\ClientTable;
//use Roles\Model\Apikeys\ClientApp;
use Roles\Model\Apikeys\ClientAppTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
            'Zend\Loader\ClassMapAutoloader' => array(
              __DIR__ . '/autoload_classmap.php',
            ),
        );
    }

    public function getServiceConfig()
    {
      return array (
        'factories' => array (
          'apiDB' => new ApiDbServiceFactory('db1'),
          'Roles\Model\Apikeys\AppTable' => function($sm) {
              $tableGateway = $sm->get('AppTableGateway');
              $table = new AppTable($tableGateway);
              return $table;
            },
          'AppTableGateway' => function ($sm) {
               $appDbAdapter = $sm->get('apiDB');
               //$resultSetPrototype = new ResultSet();
               //$resultSetPrototype->setArrayObjectPrototype( new App());
               //return new TableGateway('app', $appDbAdapter, null, $resultSetPrototype);
               return new TableGateway('app', $appDbAdapter, null);
            },
          'Roles\Model\Apikeys\ClientTable' => function($sm) {
              $tableGateway = $sm->get('ClientTableGateway');
              $table = new ClientTable($tableGateway);
              return $table;
            },
          'ClientTableGateway' => function ($sm) {
               $clientDbAdapter = $sm->get('apiDB');
               //$resultSetPrototype = new ResultSet();
               //$resultSetPrototype->setArrayObjectPrototype( new Client());
               //return new TableGateway('client', $clientDbAdapter, null, $resultSetPrototype);
               return new TableGateway('client', $clientDbAdapter, null);
            },
          'Roles\Model\Apikeys\ClientAppTable' => function($sm) {
              $tableGateway = $sm->get('ClientAppTableGateway');
              $table = new ClientAppTable($tableGateway);
              return $table;
            },
          'ClientAppTableGateway' => function ($sm) {
               $clientAppDbAdapter = $sm->get('apiDB');
               //$resultSetPrototype = new ResultSet();
               //$resultSetPrototype->setArrayObjectPrototype( new ClientApp());
               //return new TableGateway('client_app', $clientAppDbAdapter, null, $resultSetPrototype);
               return new TableGateway('client_app', $clientAppDbAdapter, null);
            },
          'locDB' => new ApiDbServiceFactory('db2'),
         )
      );
    }

    public function onBootstrap(MvcEvent $event)
    {
      $application = $event->getTarget();
      $serviceManager = $application->getServiceManager();
      $config = $serviceManager->get('Config');

      // Config json enabled exceptionStrategy
      $exceptionStrategy = new JsonExceptionStrategy();

      $displayExceptions = false;

      if (isset($config['view_manager']['display_exceptions'])) {
          $displayExceptions = $config['view_manager']['display_exceptions'];
      }

      $exceptionStrategy->setDisplayExceptions($displayExceptions);
      $exceptionStrategy->attach($application->getEventManager());
    }
}
