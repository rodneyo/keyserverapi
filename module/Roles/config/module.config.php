<?php
/**
 * Informs the service manager where to find the different controllers 
 * and view files
 */
namespace Roles;

return array(
    'controllers' => array(
        'invokables' => array(
            'Roles\Controller\Approvers' => 'Roles\Controller\ApproversController', //maps controller alias to a physical controller
            'Roles\Controller\Roles' => 'Roles\Controller\RolesController', //maps controller alias to a physical controller
        ),
    ),
    'router' => array(
        'routes' => array(
            'approvers' => array(
                'type' => 'segment',
                'options' => array(
                  'route' => '/approvers/:location/:appname',
                  'constraints' => array(
                    'location' => '[0-9]+',
                    'appname' => '[a-zA-Z][a-zA-Z0-9_-]*',
                 ),
                 'defaults' => array(
                     'controller' => 'Roles/Controller/Approvers',
                  ),
                ),
            ),
            'roles' => array(
                'type' => 'segment',
                'options' => array(
                  'route' => '/roles/:uname/:appname',
                  'constraints' => array(
                    'uname' => '[a-zA-Z].+',
                    'appname' => '[a-zA-Z][a-zA-Z0-9_-]*',
                 ),
                 'defaults' => array(
                     'controller' => 'Roles/Controller/Roles',
                  ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
          'ViewJsonStrategy',
      ),
    ),
);

