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
      'host' => 'zeus.stonemor.com',
      'useStartTls' => true,
      'accountDomainName' => 'stonemor.com',
      'accountDomainNameShort' => 'STONEMOR',
      'accountDomainCanonicalForm' => 3,
      'baseDn' => 'DC=stonemor,DC=com',
    ),
    'server2' => array(
      'host' => 'hera.stonemor.com',
      'useStartTls' => true,
      'accountDomainName' => 'stonemor.com',
      'accountDomainNameShort' => 'STONEMOR',
      'accountDomainCanonicalForm' => 3,
      'baseDn' => 'DC=stonemor,DC=com',
    ),
    'server3' => array(
      'host' => 'odin.stonemor.com',
      'useStartTls' => true,
      'accountDomainName' => 'stonemor.com',
      'accountDomainNameShort' => 'STONEMOR',
      'accountDomainCanonicalForm' => 3,
      'baseDn' => 'DC=stonemor,DC=com',
    ),
  )
);
