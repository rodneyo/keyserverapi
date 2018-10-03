<?php
/**
 * The API serivce currently needs to connect to two different databases  The db adapter service
 * factory that is shipped with ZF2 only supports connection information for one database.  This
 * custom factory add support for 2 or mor databases.  Db connection info is stored in global.php
 *  and adapters are created and stored in the service manger(Module.php).
 */
namespace Roles\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Adapter;


/**
 * ApiDbServiceFactory 
 * Custom factory to create adapters for 1 or more databases 
 * @uses FactoryInterface
 * @copyright Copyright (c) 2013 All rights reserved.
 * @author  StoneMor Partners
 */
class ApiDbServiceFactory implements FactoryInterface
{
    protected $configKey;

    public function __construct($key)
    {
        $this->configKey = $key;
    }

    /* public createService(ServiceLocatorInterface $serviceLocator)
    /**
     * createService
     * 
     * @param ServiceLocatorInterface $serviceLocator 
     * @access public
     * @return object
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        return new Adapter($config[$this->configKey]);
    }
}
