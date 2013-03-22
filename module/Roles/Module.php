<?php
namespace Roles;
use Zend\Mvc\MvcEvent;

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
        return array ();
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
