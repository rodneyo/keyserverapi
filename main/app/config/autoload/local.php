<?php
/**
 * Local Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file is to be renamed to local.php and contain configurations
 *      specific to the local envionment the app is running on.  This file SHOULD NOT be put
 *      in version control.
 */

return array(
    'ldap' => array(
        'server1' => array(
            'host' => 'odinca.stonemor.local',                   // activedirectory.stonemor.com
            'useSsl' => 'false',                         // boolean true or false
            'port' => '389',                          // 389 non-ssl 636 ssl
            'accountDomainName' => 'stonemor.local',               // stonemor.com
            'accountDomainNameShort' => 'STONEMOR',                // STONEMOR
            'baseDn' => 'DC=stonemor,DC=local',                    // DC=stonemor,DC=com
        ),
    ),
    'db1' => array(
        'username' => 'dbuser',
        'password' => '123',
    ),
    'db2' => array(
        'username' => 'dbuser',
        'password' => '123',
    ),
    /**
     * This is a service account used to bind the AD
     * on the behalf of the user.  Username is usually
     * "keyjobs" You will probably need to reset the password/unlock
     * the account in your dev AD
     */
    'client' => array(
        'username' => 'keyjobs',
        'password' => 'TrainTrack15',
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Log' => function($sm) {
                $log = new Zend\Log\Logger();
                $writer = new Zend\Log\Writer\Stream('/var/log/keyserver/keyserver.log');
                $log->addWriter($writer);
                return $log;
            }
        )
    )
);

