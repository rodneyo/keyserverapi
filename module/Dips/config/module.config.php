<?php
/**
 * Informs the service manager where to find the different controllers 
 * and view files
 */
namespace Dips;

return array(
    'controllers' => array(
        'invokables' => array(
            'Dips\Controller\Roles' => 'Dips\Controller\RolesController', //maps controller alias to a physical controller
        ),
    ),
    'router' => array(
        'routes' => array(
            'dips' => array(
                'type' => 'segment',
                'options' => array(
                  'route' => '/dips/roles/:apikey/:uname/:appname',
                  'constraints' => array(
                    'apikey' => '[a-zA-Z0-9]+',
                    'uname' => '[a-zA-Z]+',
                    'appname' => '[a-zA-Z][a-zA-Z0-9_-]*',
                 ),
                 'defaults' => array(
                     'controller' => 'Dips/Controller/Roles',
                  ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
      'not_found_template' =>  'error/404',
      'template_map' => array(
          'error/404'     => __DIR__ . '/../view/error/404.phtml'
      ),
      'template_stack_path' => array (
          'application' => __DIR__ . '/../view'
      ),
      'strategies' => array(
          'ViewJsonStrategy',
      ),
    ),
);
