<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'db1' => array(
      'driver'              => 'Pdo',
      'dsn'                 => 'mysql:dbname=apikey;host=127.0.0.1',
    ),
    'db2' => array(
      'driver'              => 'Pdo',
      'dsn'                 => 'mysql:dbname=location;host=127.0.0.1',
    ),
    //You should override this in your local.php to specify a log location on your dev machine
    'service_manager' => array(
          'factories' => array(
            'Zend\Log' => function($sm) {
                $log = new Zend\Log\Logger();
                $writer = new Zend\Log\Writer\Stream('/var/log/stonemor/app_logs/security_api.log');
                $log->addWriter($writer);
                return $log;
              }
          )
     )
);
