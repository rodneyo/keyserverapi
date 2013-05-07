<?php
namespace Roles;
use Zend\Mvc\MvcEvent;
use Roles\Model\ApiDbServiceFactory;
use Roles\Model\Apikeys\AppTable;
use Roles\Model\Apikeys\ClientTable;
use Roles\Model\Apikeys\ClientAppTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Roles\Model\Ldap;
use Roles\Model\RollUp\RollUpStoredProcedure;

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

    /* public getControllerConfig() {{{ */ 
    /**
     * getControllerConfig
     *
     * Use closure to pass in a factory to create conroller instance and inject 
     * dependencies
     * 
     * @access public
     * @return object 
     */
    public function getControllerConfig()
    {
      return array (
        'factories' => array (
          'Roles\Controller\RolesController' => function($sm) {
              $ldap = $sm->getServiceLocator()->get('Roles\Model\Ldap');
              $rollUpStoredProcedure = $sm->getServiceLocator()->get('RollUpStoredProcedure');
              $controller = new Controller\RolesController($ldap, $rollUpStoredProcedure);
              return $controller;
            },
          'Roles\Controller\ApproversController' => function($sm) {
              $rollUpStoredProcedure = $sm->getServiceLocator()->get('RollUpStoredProcedure');
              $controller = new Controller\ApproversController($rollUpStoredProcedure);
              return $controller;
            },
          'Roles\Controller\AllUsersController' => function($sm) {
              $rollUpStoredProcedure = $sm->getServiceLocator()->get('RollUpStoredProcedure');
              $controller = new Controller\AllUsersController($rollUpStoredProcedure);
              return $controller;
            }
         )
      );
    }

    public function getServiceConfig()
    {
      return array (
        'factories' => array (
          'apiDB' => new ApiDbServiceFactory('db1'),
          'rollUpDB' => new ApiDbServiceFactory('db2'),

          'RollUpStoredProcedure' => function($sm) {
              $rollUpDbAdapter = $sm->get('rollUpDB');
              $appLogger       = $sm->get('Zend\Log');
              $config          = $sm->get('config');
              $appErrorMessages= $config['appErrorMessages'];
              $rollUpStoredProcedure = new RollUpStoredProcedure(
                $rollUpDbAdapter, $appLogger, $appErrorMessages
              );
              return $rollUpStoredProcedure;
            },
          'Roles\Model\Apikeys\AppTable' => function($sm) {
              $tableGateway = $sm->get('AppTableGateway');
              $table = new AppTable($tableGateway);
              return $table;
            },
          'AppTableGateway' => function ($sm) {
               $appDbAdapter = $sm->get('apiDB');
               return new TableGateway('app', $appDbAdapter, null);
            },
          'Roles\Model\Apikeys\ClientTable' => function($sm) {
              $tableGateway = $sm->get('ClientTableGateway');
              $table = new ClientTable($tableGateway);
              return $table;
            },
          'ClientTableGateway' => function ($sm) {
               $clientDbAdapter = $sm->get('apiDB');
               return new TableGateway('client', $clientDbAdapter, null);
            },
          'Roles\Model\Apikeys\ClientAppTable' => function($sm) {
              $tableGateway = $sm->get('ClientAppTableGateway');
              $table = new ClientAppTable($tableGateway);
              return $table;
            },
          'ClientAppTableGateway' => function ($sm) {
               $clientAppDbAdapter = $sm->get('apiDB');
               return new TableGateway('client_app', $clientAppDbAdapter, null);
            },
          'Roles\Model\Ldap' => function ($sm) {
              $ldapOptions = $sm->get('config');
              $appLogger = $sm->get('Zend\Log');
              $ldap = new Ldap($ldapOptions, $appLogger);
              return $ldap;
            },
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
