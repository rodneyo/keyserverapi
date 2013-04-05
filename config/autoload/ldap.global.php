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
      //'host' => 'activedirectory.stonemor.com',
      'host' => 'zeus.stonemor.com',
      'useSsl' => true,
      'port' => 636,
      'accountDomainName' => 'stonemor.com',
      'accountDomainNameShort' => 'STONEMOR',
      'accountCanonicalForm' => 3,
      'baseDn' => 'DC=stonemor,DC=com',
    ),
  )
);
