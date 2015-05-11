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
    'ldap' => array(
        'server1' => array(
            'accountCanonicalForm' => 3,
            'networkTimeout' => 5
        ),
    ),
    'ldaptries' => 6,
    'filtergroups' => array(
        'approverfilter' => 'DIPS-TEST Field Manager Approver Group'
    ),
    'db1' => array(
      'driver'              => 'Pdo',
      'dsn'                 => 'mysql:dbname=apikey;host=127.0.0.1',
    ),
    'db2' => array(
      'driver'              => 'Pdo',
      'dsn'                 => 'mysql:dbname=rollup;host=127.0.0.1',
    ),
    'appErrorMessages' => array(
       'getLocationsByUser'  => 'Problem encountered retrieving locations',
       'getApproversByLocation'  => 'Problem retrieving field approvers',
     ),
);
