<?php
/**
 * Informs the service manager where to find the different controllers
 * and view files
 *
 * Controllers are invoked via closure factories in Module.php
 * in method getControllerConfig
 */
namespace Roles;

return array(
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
                     'controller' => 'Roles/Controller/ApproversController',
                  ),
                ),
            ),
            'alllocations' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/alllocations/:appname',
                    'constraints' => array(
                        'appname' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Roles/Controller/AllLocationsController',
                    ),
                ),
            ),
            'allusers' => array(
                'type' => 'segment',
                'options' => array(
                  'route' => '/allusers/:appname',
                  'constraints' => array(
                    'appname' => '[a-zA-Z][a-zA-Z0-9_-]*',
                 ),
                 'defaults' => array(
                     'controller' => 'Roles/Controller/AllUsersController',
                  ),
                ),
            ),
            'roles' => array(
                'type' => 'segment',
                'options' => array(
                  'route' => '/roles/:uname/:appname[/:locnames][/:aloc]',
                  'constraints' => array(
                    'uname' => '[a-zA-Z0-9]+',
                    'appname' => '[a-zA-Z0-9_-]+',
                    'locnames' => 'locnames*',
                    'aloc' => 'aloc*',
                 ),
                 'defaults' => array(
                     'controller' => 'Roles/Controller/RolesController'
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

