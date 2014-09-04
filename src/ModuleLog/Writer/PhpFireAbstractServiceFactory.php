<?php
namespace ModuleLog\Writer;

use Zend\Log\Writer\FirePhp\FirePhpBridge;
use Zend\Log\Writer\FirePhp;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PhpFireAbstractServiceFactory implements AbstractFactoryInterface
{
    /**
     * Config
     * @var array
     */
    protected $config;

    /**
     * Config Key
     * @var string
     */
    protected $configKey = 'logPhpFireFactory';
    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if ($serviceLocator instanceof AbstractPluginManager) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        $config = $this->getConfig($serviceLocator);
        if (empty($config)) {
            return false;
        }

        return (
            isset($config[$requestedName])
        );
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $this->getConfig($serviceLocator)[$requestedName];

        $configWriter['instance'] = new FirePhpBridge(\FirePHP::getInstance(true));
        // Mode settings
        if(isset($config['mode'])) {
            $configWriter['mode'] = $config['mode'];
        }
        // Log separator settings
        if(isset($config['log_separator'])) {
            $configWriter['log_separator'] = $config['log_separator'];
        }
        // Filters settings
        if(isset($config['filters'])) {
            $configWriter['filters'] = $config['filters'];
        }
        // Formatter separator settings
        if(isset($config['formatter'])) {
            $configWriter['formatter'] = $config['formatter'];
        }

        $writer = new FirePHP($configWriter);
        return $writer;
    }

    /**
     * Get model configuration, if any
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return array
     */
    protected function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        if ($this->config !== null) {
            return $this->config;
        }

        if (!$serviceLocator->has('Config')) {
            $this->config = [];
            return $this->config;
        }

        $config = $serviceLocator->get('Config');
        if (!isset($config[$this->configKey])
            || !is_array($config[$this->configKey])
        ) {
            $this->config = [];
            return $this->config;
        }

        $this->config = $config[$this->configKey];
        return $this->config;
    }

} 