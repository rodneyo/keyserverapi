<?php
/**
 * Informs the service manager where to find the different controllers 
 * and view files
 */
namespace Roles;

return array(
    'controllers' => array(
        'invokables' => array(
            'Roles\Controller\Roles' => 'Roles\Controller\RolesController', //maps controller alias to a physical controller
        ),
    ),
    'router' => array(
        'routes' => array(
            'dips' => array(
                'type' => 'segment',
                'options' => array(
                  'route' => '/roles/:apikey/:uname/:appname',
                  'constraints' => array(
                    'apikey' => '[a-zA-Z0-9]+',
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

