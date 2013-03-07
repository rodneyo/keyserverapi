StoneMor Security API
=======================

Introduction
------------
This RESTful API is accessible to authorized clients only and provides
credential and role information. The API is built with Zend
Framework 2.  

Installation
------------
 - Host:    keyserver.stonemor.com
 - OS:      Linux (CentOS)
 - API url: securityapi.stonemor.com

Current Implementations
-----------------------
DIPS: (Deposit, Invoice, PCard, Scanning).

 - Client: MTS IntellaFlow
 - Calls
    - dips/roles/[username]/[appname]
    - dips/roles/[location]/[username]
 - Returns
    - JSON object containing a list of roles from Active Directory
    - JSON object containing a list of approvers from MySQL database

Upgrading ZF2 vendor library
----------------------------
Sometimes it is a good idea to upgrade the ZF2 to bring the latest
stable updates, bug fixes and security upate.  ZF2 utilized the [**composer**](http://getcomposer.com)
package dependency manager.  Below are the steps to upgrade a ZF2
library

 - On a development server or latop
   - cd into the project root directory
   - php composer.phar update
 
 - Once the project is updated deploy project to test.  Run test suite
 - All is well deploy to production
