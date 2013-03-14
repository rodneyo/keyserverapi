<?php
return array(
    'modules' => array(
        'Roles',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            '../../../config/autoload/{,*.}{global,local}.php',
            '../../../config/autoload/{,*.}{global,local}.json',
        ),
        'module_paths' => array(
            'module',
            'vendor',
        ),
    ),
);
