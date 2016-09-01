StoneMor Security API
=======================

Introduction
------------
This a somewhat RESTful API is accessible to authorized clients only and provides
credential and role information. The API is built with Zend
Framework 2.1

Installation
------------
 - Host:    keyserver.stonemor.com
 - OS:      Linux (CentOS)
 - API url: keyserver.stonemor.com

Current Implementations
-----------------------
DIPS:       (Deposit, Invoice, PCard, Scanning)
Corpstruct: Used for role authorization 
Docscan:    Used for role authorization
CSI:        Used for role authorization

 -  End points
    - approvers/[location]/[appname]
    - allusers/[appname]
    - roles/[uname]/[appname]
 - Returns
    - JSON object containing a list of approvers for that location
    - JSON object containing a list of all users, email addresses and
      display names (dump from Active Directory)
    - JSON object containing a list of locations and AD roles the user has 
      been assigned to for an application

API Key Database
-----------------
Any client accessing the API must have a valid API key assigned.  These keys
are maintained in a mysql database on keyserver.  Currently, there is no
frontend application for maintaining the information.  Each client must include
a custom header containing the specific API assigned. 

 - Database:    api_key
 - Header:      x-stonemorapi

Unique keys can be generated by running the following command on keyserver.

```bash
./apikey-gen
```

FireFox Chrome Plugins
----
I found the these browser plugins to be very helpful for debugging and testing
[PostMan](https://www.getpostman.com/)
[REST Client](http://restclient.net)
[REST Console](http://restconsole.com)

>  With these plugins you can directly test the different endpoints, insert custom headers,
>  inspect returning JSON and a host of other things.

Running/Configuring Odinca (active directory) for Development
-------------------------------------------------------------
1.  Install VMware Fusion or VMWare Workstation (ask helpdesk for license)
1.  Import the odinca.local ova.  This can be found on the Programmer share in ODIN OVF\Odin.local-8-24-2016 (at the time of this upadte)
     - **You should copy this folder to your workstation/laptop first before importing**
1.  This will take several minutes depending on the speed of your machine
1.  When are asked to enter the name for the new guest type:

```bash
OdinCA-2016
```

1.  After successful import you will need to update the network settings for the VM to share it with your machine. Basically creating a "Bridged Network".  If you are 
unsure how to do this ask your team lead or someone in the operations group.
1.  Reboot the VM and make sure network capabilities are working properly
1.  VMWare will automatically assign an IP to the guest with dhcp.  For development we need the IP to be static.  You can configure this in VMware by doing
the following.  **NOTE:** These instructions are for VMware Fusion for Mac (OS X El Capitan 10.11.x).  Configuration on windows will be different.  Instructions will
be forth coming.

#### VMware Fusion static IP setup for dev active directory
1.  Start up the active directory (odinca) in VMware
1.  The administrator password should be "Stonemor1"
1.  Open power shell and type

```bash
getmac /v
```

1.  You should see something like below

| Connection Name | Network Adapter | Physical Address | Transport Name |
| --------------- | --------------- | ---------------- | -------------- |
| Ethernet0       | Intel(R) 82574L | 00-0C-29-F5-47-B2 | \Device\Tcpip_{D3B63090-289A-4BA1-BC84-F860EFA1725B} |

1.  Make note of the "Physical Address"
1.  On your workstation/laptop open a terminal and sudo su -

```bash
cd /Library/Preferences/VMware\ Fusion/vmnet8/
vi dhcpd.conf
```

1.  Jump down to the end of this file and enter the following

``` bash
####### VMNET DHCP Configuration. End of "DO NOT MODIFY SECTION" #######
 host OdinCA-2016 {
   hardware ethernet 00:0C:29:F5:47:B2;
   fixed-address 192.168.75.51;
 }
 ```
 
 1. Replace the "hardware ethernet" value with the value from the "Physical Address"
 1. Save it and exit out of the file
 1. Restart VMware network services.
 
 ```bash
 sudo /Applications/VMware\ Fusion.app/Contents/Library/vmnet-cli --stop
 sudo /Applications/VMware\ Fusion.app/Contents/Library/vmnet-cli --start
 ```
 
1.  Go back the active directory VM quest and reboot it
1.  Login, open power shell and type the following

```bash
ipconfig
```

1. Check the the IPv4 Address.  It should be set to the IP that you entered in the VMware dhcp.conf file

```text
Ethernet adapter Ethernet0:

   Connection-specific DNS Suffix  . : localdomain
   Link-local IPv6 Address . . . . . : fe80::903c:ed47:ecb7:28f3%12
   IPv4 Address. . . . . . . . . . . : 192.168.75.51
   Subnet Mask . . . . . . . . . . . : 255.255.255.0
   Default Gateway . . . . . . . . . : 192.168.75.2
```   

1.  If the IP is different check your dhcp.conf file again to make sure the IP  matches.  Also check the synax of the
dhcp.conf.  Ensure the indentation, brackests and semicolons are in their proper places

Upgrading ZF2 vendor library
----------------------------
Sometimes it is a good idea to upgrade the ZF2 to bring the latest
stable updates, bug fixes and security upate.  ZF2 utilized the [composer](http://getcomposer.org)
package dependency manager.  Below are the steps to upgrade a ZF2
library

 - On a development server or latop
   - cd into the project root directory
   - php composer.phar update
 
 - Once the project is updated deploy project to test.
 - All is well deploy to production
